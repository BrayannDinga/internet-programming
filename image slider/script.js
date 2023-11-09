let slideIndex = 1; /* Initialize a variable to keep track of the current slide index */
showSlides(slideIndex); /* Call the showSlides function to start the slideshow */

function plusSlides(n) { /* Define a function to navigate to the previous or next slide */
    showSlides(slideIndex += n);
}

function currentSlide(n) { 
    showSlides(slideIndex = n);
}

function showSlides(n) { /* Define the function to control the slideshow */
    let i; /* Declare a variable for iteration */
    const slides = document.getElementsByClassName("mySlides"); /* Get all elements with the class "mySlides" */
    const dots = document.getElementsByClassName("dot"); /* Get all elements with the class "dot" */
    if (n > slides.length) {
        slideIndex = 1; /* Reset the index to 1 if it goes beyond the number of slides */
    }
    if (n < 1) {
        slideIndex = slides.length; /* If the index is less than 1, set it to the last slide */
    }
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none"; /* Hide all slides by default */
    }
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", ""); /* Remove the "active" class from all dots */
    }
    slides[slideIndex - 1].style.display = "block"; /* Display the current slide */
    dots[slideIndex - 1].className += " active"; /* Add the "active" class to the current dot */
    // Add a delay (pause) between slides (in milliseconds)
    const delay = 2000; // 2 seconds

    setTimeout(function() {
        plusSlides(1); // Automatically transition to the next slide after the delay
    }, delay);
}