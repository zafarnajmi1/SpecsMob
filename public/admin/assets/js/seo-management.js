document.addEventListener('DOMContentLoaded', function() {
    // Character counters and validation
    const metaTitle = document.getElementById('meta_title');
    const metaDescription = document.getElementById('meta_description');
    
    if (metaTitle) {
        metaTitle.addEventListener('input', function() {
            updateCharacterCount(this, 'metaTitleCount', 60);
            updatePreview('previewTitle', this.value);
            validateLength(this, 'metaTitleGood', 'metaTitleWarning', 60);
        });
        // Initialize
        updateCharacterCount(metaTitle, 'metaTitleCount', 60);
        validateLength(metaTitle, 'metaTitleGood', 'metaTitleWarning', 60);
    }
    
    if (metaDescription) {
        metaDescription.addEventListener('input', function() {
            updateCharacterCount(this, 'metaDescCount', 160);
            updatePreview('previewDescription', this.value);
            validateLength(this, 'metaDescGood', 'metaDescWarning', 160);
        });
        // Initialize
        updateCharacterCount(metaDescription, 'metaDescCount', 160);
        validateLength(metaDescription, 'metaDescGood', 'metaDescWarning', 160);
    }
    
    // Auto-fill OG and Twitter fields
    if (metaTitle) {
        metaTitle.addEventListener('blur', function() {
            const ogTitle = document.getElementById('og_title');
            const twitterTitle = document.getElementById('twitter_title');
            
            if (!ogTitle.value && this.value) {
                ogTitle.value = this.value;
            }
            if (!twitterTitle.value && this.value) {
                twitterTitle.value = this.value;
            }
        });
    }
    
    if (metaDescription) {
        metaDescription.addEventListener('blur', function() {
            const ogDescription = document.getElementById('og_description');
            const twitterDescription = document.getElementById('twitter_description');
            
            if (!ogDescription.value && this.value) {
                ogDescription.value = this.value;
            }
            if (!twitterDescription.value && this.value) {
                twitterDescription.value = this.value;
            }
        });
    }
    
    // Schema markup validation
    const schemaMarkup = document.getElementById('schema_markup');
    if (schemaMarkup) {
        schemaMarkup.addEventListener('blur', function() {
            try {
                if (this.value.trim()) {
                    JSON.parse(this.value);
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                }
            } catch (e) {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
                console.error('Invalid JSON in schema markup:', e);
            }
        });
    }
    
    function updateCharacterCount(element, countElementId, maxLength) {
        const countElement = document.getElementById(countElementId);
        if (countElement) {
            countElement.textContent = element.value.length;
            
            if (element.value.length > maxLength) {
                countElement.classList.add('text-danger');
                countElement.classList.remove('text-success');
            } else if (element.value.length >= maxLength * 0.8) {
                countElement.classList.add('text-warning');
                countElement.classList.remove('text-success', 'text-danger');
            } else {
                countElement.classList.add('text-success');
                countElement.classList.remove('text-warning', 'text-danger');
            }
        }
    }
    
    function validateLength(element, goodElementId, warningElementId, maxLength) {
        const goodElement = document.getElementById(goodElementId);
        const warningElement = document.getElementById(warningElementId);
        
        if (element.value.length <= maxLength && element.value.length >= 30) {
            goodElement.style.display = 'inline';
            warningElement.style.display = 'none';
        } else if (element.value.length > maxLength) {
            goodElement.style.display = 'none';
            warningElement.style.display = 'inline';
        } else {
            goodElement.style.display = 'none';
            warningElement.style.display = 'none';
        }
    }
    
    function updatePreview(elementId, value) {
        const previewElement = document.getElementById(elementId);
        if (previewElement && value) {
            previewElement.textContent = value;
        }
    }
});