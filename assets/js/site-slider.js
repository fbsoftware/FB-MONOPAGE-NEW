document.addEventListener("DOMContentLoaded", function () {

    document
        .querySelectorAll(".widget-slider")
        .forEach(function (slider) {

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

            const activeIndex = slides.findIndex(slide =>
                slide.classList.contains("active")
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

                slides.forEach((slide, i) => {
                    slide.classList.toggle("active", i === index);
                });

                dots.forEach((dot, i) => {
                    dot.classList.toggle("active", i === index);
                });

                current = index;
            }

            if (nextBtn) {
                nextBtn.onclick = function (event) {
                    event.preventDefault();
                    showSlide(current + 1);
                };
            }

            if (prevBtn) {
                prevBtn.onclick = function (event) {
                    event.preventDefault();
                    showSlide(current - 1);
                };
            }

            dots.forEach(dot => {

                dot.onclick = function (event) {
                    event.preventDefault();

                    const index = parseInt(
                        dot.dataset.slide,
                        10
                    );

                    if (!Number.isNaN(index)) {
                        showSlide(index);
                    }
                };
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
                setInterval(function () {
                    showSlide(current + 1);
                }, interval);
            }

            showSlide(current);
        });
});