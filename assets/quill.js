import Quill from 'quill';
import 'quill/dist/quill.snow.css';  // Assure-toi que le CSS est bien chargé

// Initialize Quill editor
document.addEventListener('DOMContentLoaded', () => {
    var quillElements = document.querySelectorAll('.quill-editor');
    quillElements.forEach((element) => {
        new Quill(element, {
            theme: 'snow',
        });
    });
});