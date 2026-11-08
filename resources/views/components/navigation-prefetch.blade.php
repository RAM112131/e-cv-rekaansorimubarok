<script type="speculationrules">
    {
        "prefetch": [{
            "source": "document",
            "where": {
                "and": [
                    {"href_matches": "/*"},
                    {"not": {"href_matches": "/admin/*"}},
                    {"not": {"href_matches": "/cv/download"}}
                ]
            },
            "eagerness": "moderate"
        }]
    }
</script>
