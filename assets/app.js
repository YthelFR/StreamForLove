/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import Quill from 'quill';
import 'quill/dist/quill.snow.css';

document.addEventListener('DOMContentLoaded', () => {
    const quillElements = document.querySelectorAll('.quill-editor');

    quillElements.forEach(el => {
        const quill = new Quill(el, {
            theme: 'snow',
            modules: {
                toolbar: true
            }
        });

        // Mettre à jour le champ caché lorsque le contenu de Quill change
        quill.on('text-change', () => {
            const hiddenField = document.querySelector(`#${el.id}-hidden`);
            if (hiddenField) {
                hiddenField.value = quill.root.innerHTML;
            }
        });
    });

    // Assurer que les champs cachés sont mis à jour avant la soumission du formulaire
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', () => {
            const editors = document.querySelectorAll('.quill-editor');
            editors.forEach(editor => {
                const hiddenField = document.querySelector(`#${editor.id}-hidden`);
                if (hiddenField) {
                    hiddenField.value = Quill.find(editor).root.innerHTML;
                }
            });
        });
    });
});