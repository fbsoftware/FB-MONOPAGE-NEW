(function () {
    
console.log("SITE SLIDER JS CARICATO");

    function initSlider(slider) {

        if (!slider) return;
console.log(    "WIDGET SLIDER TROVATI:",
    document.querySelectorAll(".widget-slider").length
);
        const slides = Array.from(
            slider.querySelectorAll(".slide")
        );

        const dots = Array.from(
            slider.querySelectorAll(".dot")
        );

        const nextBtn = slider.querySelector(".next");
        const prevBtn = slider.querySelector(".prev");

        if (!slides.length) return;

        let current = 0;

        const activeIndex = slides.findIndex(
            slide => slide.classList.contains("active")
        );

        if (activeIndex >= 0) {
            current = activeIndex;
        }

        function showSlide(index) {

            if (index >= slides.length) {
                index = 0;
            }

            if (index < 0) {
                index = slides.length - 1;
            }

            slides.forEach(function (slide, i) {
                slide.classList.toggle(
                    "active",
                    i === index
                );
            });

            dots.forEach(function (dot, i) {
                dot.classList.toggle(
                    "active",
                    i === index
                );
            });

            current = index;
        }

        if (slider._intervalId) {
            clearInterval(slider._intervalId);
        }

        if (nextBtn) {
            nextBtn.addEventListener(
                "click",
                function () {
                    showSlide(current + 1);
                }
            );
        }

        if (prevBtn) {
            prevBtn.addEventListener(
                "click",
                function () {
                    showSlide(current - 1);
                }
            );
        }

        dots.forEach(function (dot) {

            dot.addEventListener(
                "click",
                function () {

                    const index = parseInt(
                        dot.dataset.slide,
                        10
                    );

                    if (!Number.isNaN(index)) {
                        showSlide(index);
                    }
                }
            );
        });

        const interval = parseInt(
            slider.dataset.interval || "5000",
            10
        );

        if (
            Number.isFinite(interval) &&
            interval > 0 &&
            slides.length > 1
        ) {
            slider._intervalId = setInterval(
                function () {
                    showSlide(current + 1);
                },
                interval
            );
        }

        showSlide(current);
    }

 document.addEventListener(
        "DOMContentLoaded",
        function () {

            console.log(
                "WIDGET SLIDER TROVATI:",
                document.querySelectorAll(".widget-slider").length
            );

            document
                .querySelectorAll(".widget-slider")
                .forEach(initSlider);
        }
    );

})();