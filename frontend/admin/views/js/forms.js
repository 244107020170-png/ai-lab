
// 1. Listen for the file selection
document.getElementById('photo_input').addEventListener('change', function (event) {

    // 2. Get the selected file
    const file = event.target.files[0];

    if (file) {
        // 3. Create a FileReader to read the file from memory
        const reader = new FileReader();

        // 4. When the reader finishes, update the Image src
        reader.onload = function (e) {
            document.getElementById('preview-img').src = e.target.result;
        }

        // 5. Read the file as a Data URL (base64 string)
        reader.readAsDataURL(file);

        // Optional: Update the text "No file chosen" to the filename
        document.getElementById('file-name').textContent = file.name;

        // Optional: Show the "Unsaved Changes" bar
        document.querySelector('.save-bar').style.display = 'flex';
    }
});
