function fetchSuggestions() {
    var query = document.getElementById('search-input').value;

    if (query.trim() === '') {
        document.getElementById('suggestions').style.display = 'none';
        return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'search.php?query=' + encodeURIComponent(query), true);

    xhr.onload = function() {
        if (xhr.status === 200) {
            var suggestions = JSON.parse(xhr.responseText);
            var suggestionsContainer = document.getElementById('suggestions');
            suggestionsContainer.innerHTML = '';  // Clear any previous suggestions

            if (suggestions.length > 0) {
                suggestionsContainer.style.display = 'block';

                suggestions.forEach(function(suggestion) {
                    var suggestionElement = document.createElement('div');
                    suggestionElement.className = 'suggestion-item';
                    suggestionElement.innerHTML = `<a href="product_details.php?id=${suggestion.id}">${suggestion.name}</a>`;
                    suggestionsContainer.appendChild(suggestionElement);
                });
            } else {
                suggestionsContainer.style.display = 'none';
            }
        }
    };

    xhr.send();
}

function vsearch() {
    var query = document.getElementById('search-input').value;
    if (query.trim() === '') {
        alert('Please enter a search term');
        return false;
    }
    return true;
}

document.getElementById('search-input').addEventListener('input', fetchSuggestions);

document.addEventListener('DOMContentLoaded', function() {
    var sliders = document.querySelectorAll('.product-slider');

    sliders.forEach(function(slider) {
        var slides = slider.querySelectorAll('.product-slide');
        var index = 0;

        function moveSlides() {
            slides.forEach((slide, i) => {
                slide.style.transform = `translateX(-${index * 100}%)`;
            });
        }

        function nextSlide() {
            index = (index + 1) % slides.length;
            moveSlides();
        }

        setInterval(nextSlide, 3000);

        // Initialize first position
        moveSlides();
    });
});

$(document).ready(function() {
    $('#menu-icon').click(function() {
        $('.category-sidebar').toggleClass('open');
    });
});

document.getElementById('menu-toggle').addEventListener('click', function() {
    var sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('open');
});

function toggleSidebar() {
    const sidebar = document.querySelector('.category-sidebar');
    sidebar.classList.toggle('open');
}

  // Slideshow functionality
  document.addEventListener('DOMContentLoaded', function() {
    let slideIndex = 0;
    const slides = document.querySelectorAll('.indexSlides');
    const totalSlides = slides.length;
    
    // Function to show next slide
    function showSlides() {
        // Hide all slides
        slides.forEach(slide => {
            slide.classList.remove('active');
        });
        
        // Move to next slide
        slideIndex++;
        if (slideIndex >= totalSlides) {
            slideIndex = 0;
        }
        
        // Show current slide
        slides[slideIndex].classList.add('active');
        
        // Change slide every 4 seconds
        setTimeout(showSlides, 4000);
    }
    
    // Start slideshow if slides exist
    if (totalSlides > 0) {
        // Show the first slide immediately
        slides[0].classList.add('active');
        
        // Start the slideshow
        setTimeout(showSlides, 4000);
    }
});
document.querySelectorAll('.product-slider').forEach(slider => {
    const slideWrapper = slider.querySelector('.product-slide-wrapper');
    const slideLeft = slider.querySelector('.slide-left');
    const slideRight = slider.querySelector('.slide-right');

    let scrollAmount = 0;
    const slideWidth = slider.offsetWidth;

    slideRight.addEventListener('click', () => {
        if (scrollAmount < slideWrapper.scrollWidth - slideWidth) {
            scrollAmount += slideWidth;
            slideWrapper.style.transform = `translateX(-${scrollAmount}px)`;
        }
    });

    slideLeft.addEventListener('click', () => {
        if (scrollAmount > 0) {
            scrollAmount -= slideWidth;
            slideWrapper.style.transform = `translateX(-${scrollAmount}px)`;
        }
    });
});

document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', function(event) {
        event.preventDefault();
        const url = this.href;

        // Use AJAX to add the product to the cart without redirecting
        $.get(url, function(response) {
            // Show modal after product is added to the cart
            document.getElementById('cartModal').style.display = 'block';
        });
    });
});

document.getElementById('continueShopping').addEventListener('click', function() {
    // Hide the modal and return to the product page
    document.getElementById('cartModal').style.display = 'none';
    window.location.href = 'product.php';
});

document.getElementById('viewCart').addEventListener('click', function() {
    // Redirect to the cart page
    window.location.href = 'cart.php';
});