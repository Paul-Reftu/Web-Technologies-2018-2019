var slideNo = 0;
slideshow();

function slideshow() {
	var slides = document.getElementsByClassName("slide");

	for (var i = 0; i < slides.length; i++)
		slides[i].style.display = "none";

	if (slideNo >= slides.length)
		slideNo = 0;

	slides[slideNo].style.display = "flex";
	slideNo++;
	setTimeout(slideshow, 3500);
}

