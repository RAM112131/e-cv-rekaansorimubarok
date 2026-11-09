document.addEventListener('click', (event) => {
    if (!(event.target instanceof Element)) {
        return;
    }

    const closeButton = event.target.closest('[data-close-skill-modal]');
    if (closeButton) {
        const node = closeButton.closest('.skill-node');
        node.style.zIndex = '40';
        node.querySelector('.skill-modal')?.classList.add('hidden');
        return;
    }

    if (event.target.closest('.skill-modal')) {
        return;
    }

    const trigger = event.target.closest('[data-skill-toggle]');
    if (trigger) {
        const currentModal = trigger.querySelector('.skill-modal');

        document.querySelectorAll('.skill-modal').forEach((modal) => {
            if (modal !== currentModal) {
                modal.classList.add('hidden');
                modal.closest('.skill-node').style.zIndex = '40';
            }
        });

        const node = trigger.closest('.skill-node');
        const isHidden = currentModal.classList.contains('hidden');
        currentModal.classList.toggle('hidden', !isHidden);
        node.style.zIndex = isHidden ? '100' : '40';

        return;
    }

    if (!event.target.closest('.skill-node')) {
        document.querySelectorAll('.skill-modal').forEach((modal) => {
            modal.classList.add('hidden');
            modal.closest('.skill-node').style.zIndex = '40';
        });
    }
});

const skillDataElement = document.getElementById('home-skill-data');
const levelElement = document.getElementById('sun-skill-level');
const nameElement = document.getElementById('sun-skill-name');
const percentageContainer = document.getElementById('dynamic-percentage');

if (skillDataElement && levelElement && nameElement) {
    const skills = JSON.parse(skillDataElement.textContent || '[]');
    let lastIndex = -1;
    let timer;

    const showNextSkill = () => {
        if (document.hidden || skills.length === 0) {
            return;
        }

        let nextIndex = 0;
        if (skills.length > 1) {
            do {
                nextIndex = Math.floor(Math.random() * skills.length);
            } while (nextIndex === lastIndex);
        }

        lastIndex = nextIndex;
        const skill = skills[nextIndex];

        if (percentageContainer) {
            percentageContainer.style.transition = 'opacity 0.15s ease, transform 0.15s ease';
            percentageContainer.style.opacity = '0';
            percentageContainer.style.transform = 'scale(0.92)';
        }

        nameElement.style.transition = 'opacity 0.15s ease';
        nameElement.style.opacity = '0';

        timer = window.setTimeout(() => {
            levelElement.innerText = skill.level;
            nameElement.innerText = skill.name;

            if (percentageContainer) {
                percentageContainer.style.opacity = '1';
                percentageContainer.style.transform = 'scale(1)';
            }

            nameElement.style.opacity = '1';
            timer = window.setTimeout(showNextSkill, 1350);
        }, 150);
    };

    document.addEventListener('visibilitychange', () => {
        window.clearTimeout(timer);
        timer = undefined;

        if (!document.hidden) {
            showNextSkill();
        }
    });

    showNextSkill();
}
