import * as lightningcss from 'lightningcss';

/**
 * Unwrap CSS @layer blocks to ensure full compatibility with
 * browsers that don't support native CSS @layer (e.g. Android WebView / default mobile browsers < Chrome 99).
 */
function unwrapLayers(css) {
    let result = '';
    let i = 0;
    const len = css.length;

    while (i < len) {
        if (css.startsWith('@layer', i)) {
            const nextSemi = css.indexOf(';', i);
            const nextBrace = css.indexOf('{', i);

            // Handle @layer components; (declarations without body)
            if (nextSemi !== -1 && (nextBrace === -1 || nextSemi < nextBrace)) {
                i = nextSemi + 1;
                continue;
            }

            // Handle @layer name { ... }
            if (nextBrace !== -1) {
                let braceDepth = 1;
                let j = nextBrace + 1;
                while (j < len && braceDepth > 0) {
                    if (css[j] === '{') braceDepth++;
                    else if (css[j] === '}') braceDepth--;
                    j++;
                }
                const layerContent = css.substring(nextBrace + 1, j - 1);
                result += unwrapLayers(layerContent);
                i = j;
                continue;
            }
        }

        result += css[i];
        i++;
    }

    return result;
}

/**
 * Vite plugin that transpiles modern Tailwind CSS v4 features (oklch, oklab, color-mix, @layer)
 * down to standard sRGB / hex colors and flat CSS rules for older Android and iOS browsers.
 */
export default function cssCompatibilityPlugin() {
    return {
        name: 'vite-plugin-css-compatibility',
        enforce: 'post',
        generateBundle(options, bundle) {
            for (const [fileName, asset] of Object.entries(bundle)) {
                if (fileName.endsWith('.css') && (asset.type === 'asset' || typeof asset.source !== 'undefined')) {
                    const originalCss = typeof asset.source === 'string'
                        ? asset.source
                        : asset.source.toString();

                    try {
                        // 1. Unwrap @layer rules
                        const unwrapped = unwrapLayers(originalCss);

                        // 2. Transpile modern color spaces (oklch, oklab, color-mix) via lightningcss
                        const targets = {
                            chrome: 70 << 16,
                            android: 70 << 16,
                            safari: 13 << 16,
                            ios_saf: 13 << 16,
                            firefox: 70 << 16
                        };

                        const transformed = lightningcss.transform({
                            filename: fileName,
                            code: Buffer.from(unwrapped),
                            minify: true,
                            targets
                        });

                        asset.source = transformed.code;
                        console.log(`[css-compat] Processed ${fileName} for legacy browser compatibility (oklch -> sRGB, @layer unwrapped).`);
                    } catch (err) {
                        console.warn(`[css-compat] Failed to process ${fileName}:`, err.message);
                    }
                }
            }
        }
    };
}

