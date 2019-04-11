var slideNo = 0;
var slideTimeout = 4100;
var slideshowImages = ["assets/images/slide_1.jpg",
	"assets/images/slide_2.jpg",
	"assets/images/slide_3.jpg"];
var slideshowHeaders = ["Protect what is yours.",
			"Know the latest security threats.",
			"Learn how to engineer \"hackproof\" systems."];
/* Image source (slide_1.jpg): https://www.telindus.nl/mircosegmentatie-software-defined-datacenter/ 
	Image source (slide_2.jpg): https://www.compli.com/blog/the-best-defense-against-cyber-attacks/
	Image source (slide_3.jpg): https://wallpaperplay.com/board/java-programming-wallpapers */

	slideshow();

function slideshow() {
	var slide = document.getElementById("slide");
	slide.classList.add("fadeOut");
	setTimeout(function() {
		document.getElementById("slide").querySelector("img").src = 
			slideshowImages[slideNo];
		document.getElementById("slide").querySelector("h2").innerHTML = 
			slideshowHeaders[slideNo];
		slide.classList.remove("fadeOut");
	}, 1600);

	slideNo++;

	if (slideNo == slideshowImages.length)
		slideNo = 0;

	setTimeout(slideshow, slideTimeout);
}

