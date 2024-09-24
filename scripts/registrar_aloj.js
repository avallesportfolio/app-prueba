document.addEventListener("DOMContentLoaded", function() {
    var currentStage = 1;
    showStage(currentStage);

    document.querySelectorAll(".nextBtn").forEach(function(button) {
        button.addEventListener("click", function() {
            var nextStage = this.getAttribute("data-next");
            currentStage++;
            showStage(currentStage);
        });
    });

    document.querySelectorAll(".prevBtn").forEach(function(button) {
        button.addEventListener("click", function() {
            var prevStage = this.getAttribute("data-prev");
            currentStage--;
            showStage(currentStage);
        });
    });

    document.querySelector(".submitBtn").addEventListener("click", function() {
        currentStage++;
        showStage(currentStage);
    });

    function showStage(stage) {
        document.querySelectorAll(".stage").forEach(function(element) {
            element.style.display = "none";
        });
        document.getElementById("stage" + stage).style.display = "block";

        // Update button visibility
        if (stage === 1) {
            document.querySelector(".prevBtn").style.display = "none";
            document.querySelector(".nextBtn").style.display = "block";
            document.querySelector(".submitBtn").style.display = "none";
        } else if (stage === 6) {
            document.querySelector(".prevBtn").style.display = "block";
            document.querySelector(".nextBtn").style.display = "none";
            document.querySelector(".submitBtn").style.display = "block";
        } else if (stage === 7) {
            document.querySelector(".prevBtn").style.display = "none";
            document.querySelector(".nextBtn").style.display = "none";
            document.querySelector(".submitBtn").style.display = "none";
        } else {
            document.querySelector(".prevBtn").style.display = "block";
            document.querySelector(".nextBtn").style.display = "block";
            document.querySelector(".submitBtn").style.display = "none";
        }
    }
});
