<footer class="bg-white border-top py-4 mt-5">
    <div class="container text-center">
        <p class="text-muted small mb-0">© 2026 <strong>NToday Media</strong>. All Rights Reserved.</p>
        <p class="text-muted small">Reporter Dashboard v2.0</p>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<script>
    // మీరు పంపిన పాత JS కోడ్ అంతా ఇక్కడ ఉంటుంది
    $(document).ready(function() {
        if ($('#editor').length) {
            ClassicEditor.create(document.querySelector('#editor')).catch(e => console.error(e));
        }
        
        // ... (Image Cropping & Category Logic) ...
        // (నేను పైన ఇచ్చిన 'add_news.php' లోని స్క్రిప్ట్ ని ఇక్కడికి మూవ్ చేసుకోవచ్చు లేదా అక్కడే ఉంచవచ్చు)
    });
</script>

</body>
</html>