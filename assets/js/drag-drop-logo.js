
// Images Types
const imagesTypes = [
    "jpeg",
    "png",
    "svg",
    "gif"
];


for (var i=0;i<=10;i++) {
    // Select Drop-Zoon Area
    let dropZoon = document.querySelector('#dropZoon_'+i+'');
    if (dropZoon) {

		// Loading Text
		let loadingText = document.querySelector('#loadingText_' + i + '');

		// Slect File Input
		let fileInput = document.querySelector('#fileInput_' + i + '');

		if (fileInput) {

			// Select Preview Image
			let previewImage = document.querySelector('#previewImage_' + i + '');

			// When (drop-zoon) has (dragover) Event
			dropZoon.addEventListener('dragover', function (event) {
				// Prevent Default Behavior
				event.preventDefault();
				// Add Class (drop-zoon--over) On (drop-zoon)
				$('div#dropZoon_0').addClass('drop-zoon--over');
				//dropZoon.classList.add('drop-zoon--over');
			});

			// When (drop-zoon) has (dragleave) Event
			dropZoon.addEventListener('dragleave', function (event) {
				// Remove Class (drop-zoon--over) from (drop-zoon)
				dropZoon.classList.remove('drop-zoon--over');
			});

			// When (drop-zoon) has (drop) Event
			dropZoon.addEventListener('drop', function (event) {
				// Prevent Default Behavior
				event.preventDefault();

				// Remove Class (drop-zoon--over) from (drop-zoon)
				dropZoon.classList.remove('drop-zoon--over');

				// Select The Dropped File
				const file = event.dataTransfer.files[0];

				// Call Function uploadFile(), And Send To Her The Dropped File :)
				uploadFile(file);
			});

			// When (drop-zoon) has (click) Event
			dropZoon.addEventListener('click', function (event) {
				// Click The (fileInput)
				fileInput.click();
			});

			// When (fileInput) has (change) Event
			fileInput.addEventListener('change', function (event) {
				// Select The Chosen File
				const file = event.target.files[0];

				// Call Function uploadFile(), And Send To Her The Chosen File :)
				uploadFile(file, dropZoon, loadingText, previewImage);
			});
		}
	}

}


// Upload File Function
function uploadFile(file,dropZoon,loadingText,previewImage) {
    // FileReader()
    const fileReader = new FileReader();
    // File Type
    const fileType = file.type;
    // File Size
    const fileSize = file.size;

    // If File Is Passed from the (File Validation) Function
    if (fileValidate(fileType, fileSize)) {
        // Add Class (drop-zoon--Uploaded) on (drop-zoon)
        dropZoon.classList.add('drop-zoon--Uploaded');

        // Show Loading-text
        loadingText.style.display = "block";
        // Hide Preview Image
        previewImage.style.display = 'none';

        // After File Reader Loaded
        fileReader.addEventListener('load', function () {
            // After Half Second
            setTimeout(function () {
                // Hide Loading-text (please-wait) Element
                loadingText.style.display = "none";
                // Show Preview Image
                previewImage.style.display = 'block';
            }, 500); // 0.5s

            // Add The (fileReader) Result Inside (previewImage) Source
            previewImage.setAttribute('src', fileReader.result);
            // Call Function progressMove();
        });

        fileReader.readAsDataURL(file);
    } else { // Else

        this; // (this) Represent The fileValidate(fileType, fileSize) Function

    };
};

// Simple File Validate Function
function fileValidate(fileType, fileSize) {
    // File Type Validation
    let isImage = imagesTypes.filter((type) => fileType.indexOf(`image/${type}`) !== -1);

    // If The Uploaded File Type Is 'jpeg'
    if (isImage[0] === 'jpeg') {
        // Add Inisde (uploadedFileIconText) The (jpg) Value
        // uploadedFileIconText.innerHTML = 'jpg';
    } else { // else
        // Add Inisde (uploadedFileIconText) The Uploaded File Type
        // uploadedFileIconText.innerHTML = isImage[0];
    };

    // If The Uploaded File Is An Image
    if (isImage.length !== 0) {
        // Check, If File Size Is 2MB or Less
        if (fileSize <= 2000000) { // 2MB :)
            return true;
        } else { // Else File Size
            return alert('Please Your File Should be 2 Megabytes or Less');
        };
    } else { // Else File Type
        return alert('Please make sure to upload An Image File Type');
    };
};

// :)
