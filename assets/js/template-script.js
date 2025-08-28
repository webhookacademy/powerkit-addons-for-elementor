(function() {
    const options = {
        template: [
            { text: "Category", value: "" },
            { text: "Turbo Template", value: "turbo-template" },
            { text: "Sales", value: "turbo-sales" }
        ],
        section: [
            { text: "Category", value: "" },
            { text: "Street Food", value: "street-food" }
        ]
    };

    function populateSelect(type) {
        const select = document.getElementById("pkae-elementor-template-library-filter-theme");
        if (!select) return;

        select.innerHTML = "";

        let list = [];
        let defaultValue = "";

        if (type === "all") {

            const templateLabel = document.createElement("option");
            templateLabel.textContent = "---------Templates---------";
            templateLabel.disabled = true;
            select.appendChild(templateLabel);

            options.template.forEach(opt => {
                const o = document.createElement("option");
                o.value = opt.value;
                o.textContent = opt.text;
                select.appendChild(o);
            });

            const sectionLabel = document.createElement("option");
            sectionLabel.textContent = "---------Sections---------";
            sectionLabel.disabled = true;
            select.appendChild(sectionLabel);

            options.section.forEach(opt => {
                const o = document.createElement("option");
                o.value = opt.value;
                o.textContent = opt.text;
                select.appendChild(o);
            });
            defaultValue = "";
        } else if (options[type]) {
            list = options[type];
            defaultValue = type === "template" ? "turbo-template" : "street-food";
        }

        list.forEach(opt => {
            const o = document.createElement("option");
            o.value = opt.value;
            o.textContent = opt.text;
            select.appendChild(o);
        });

        jQuery(select)
            .val(defaultValue)
            .trigger("change")
            .select2({
                placeholder: "Category",
                width: 'resolve',
                allowClear: false
            });
    }

    document.addEventListener("click", function(e) {
        if (e.target.classList.contains("pkae-tab-btn")) {
            const type = e.target.getAttribute("data-type");

            document.querySelectorAll(".pkae-tab-btn").forEach(btn =>
                btn.classList.remove("pkae-tab-active")
            );
            e.target.classList.add("pkae-tab-active");

            populateSelect(type);
        }
    });

    const checkInterval = setInterval(() => {
        const defaultBtn = document.querySelector('.pkae-tab-btn[data-type="all"]');
        const selectBox = document.getElementById("pkae-elementor-template-library-filter-theme");

        if (defaultBtn && selectBox) {
            defaultBtn.click();
            clearInterval(checkInterval);
        }
    }, 200);
})();


(function() {
    document.addEventListener("input", function(e) {
        if (e.target && e.target.id === "pkae-template-search") {
            const keyword = e.target.value.toLowerCase();

            document.querySelectorAll('.pkae-item, h2.pkae-templates-library-template-category').forEach(el => {
                const title = el.textContent.toLowerCase();
                if (title.includes(keyword)) {
                    el.style.display = '';
                } else {
                    el.style.display = 'none';
                }
            });
        }
    });
})();