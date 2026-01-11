/**
 * JetFormBuilder Bulk Operations Handler
 * Syncs listing grid checkboxes with JetFormBuilder form field
 */

(function($) {
    'use strict';
    
    // Configuration
    const CONFIG = {
        formId: 13463,
        checkboxSelector: 'input[name="checked[]"].darbi-checkbox',
        targetFieldName: 'checked'
    };
    
    /**
     * Get the JetFormBuilder form instance
     */
    function getJetForm() {
        const form = document.querySelector(`[data-form-id="${CONFIG.formId}"]`);
        if (!form) {
            console.warn(`JetFormBuilder form with ID ${CONFIG.formId} not found`);
            return null;
        }
        return form;
    }
    
    /**
     * Get the target checkbox field in JetFormBuilder
     */
    function getTargetField() {
        const form = getJetForm();
        if (!form) return null;
        
        // Try different selectors for the eksportam field
        let field = form.querySelector(`[name="${CONFIG.targetFieldName}[]"]`);
        if (!field) {
            field = form.querySelector(`[data-field-name="${CONFIG.targetFieldName}"]`);
        }
        
        return field;
    }
    
    /**
     * Get all checked values from listing grid
     */
    function getCheckedValues() {
        const checkboxes = document.querySelectorAll(CONFIG.checkboxSelector);
        const values = [];
        
        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                values.push(checkbox.value);
            }
        });
        
        return values;
    }
    
    /**
     * Update JetFormBuilder field with checked values
     */
    function updateJetFormField(values) {
        const form = getJetForm();
        if (!form) return;
        
        // Find all checkboxes in the eksportam field
        const fieldCheckboxes = form.querySelectorAll(
            `[name="${CONFIG.targetFieldName}[]"], [data-field-name="${CONFIG.targetFieldName}"]`
        );
        
        if (fieldCheckboxes.length === 0) {
            console.warn(`Field "${CONFIG.targetFieldName}" not found in form`);
            return;
        }
        
        // First, uncheck all
        fieldCheckboxes.forEach(cb => {
            cb.checked = false;
        });
        
        // Then check matching values
        values.forEach(value => {
            fieldCheckboxes.forEach(cb => {
                if (cb.value === value) {
                    cb.checked = true;
                    // Trigger change event for JetFormBuilder
                    cb.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
        });
        
        console.log(`Updated ${values.length} values in JetFormBuilder field`);
    }
    
    /**
     * Create or update a hidden field with JSON data (alternative approach)
     */
    function updateHiddenField(values) {
        const form = getJetForm();
        if (!form) return;
        
        let hiddenField = form.querySelector(`input[name="${CONFIG.targetFieldName}_bulk"]`);
        
        if (!hiddenField) {
            hiddenField = document.createElement('input');
            hiddenField.type = 'hidden';
            hiddenField.name = `${CONFIG.targetFieldName}_bulk`;
            form.appendChild(hiddenField);
        }
        
        hiddenField.value = JSON.stringify(values);
    }
    
    /**
     * Handle checkbox change events
     */
    function handleCheckboxChange() {
        const checkedValues = getCheckedValues();
        
        // Update the JetFormBuilder field
        updateJetFormField(checkedValues);
        
        // Also store in hidden field as backup
        updateHiddenField(checkedValues);
        
        // Dispatch custom event for other scripts
        document.dispatchEvent(new CustomEvent('jetform:bulkUpdated', {
            detail: { values: checkedValues }
        }));
    }
    
    /**
     * Initialize the handler
     */
    function init() {
        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
            return;
        }
        
        // Attach event listeners to all checkboxes
        const checkboxes = document.querySelectorAll(CONFIG.checkboxSelector);
        
        if (checkboxes.length === 0) {
            console.warn('No checkboxes found with selector:', CONFIG.checkboxSelector);
            return;
        }
        
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', handleCheckboxChange);
        });
        
        console.log(`JetFormBuilder bulk handler initialized with ${checkboxes.length} checkboxes`);
        
        // Initial sync
        handleCheckboxChange();
    }
    
    /**
     * Public API
     */
    window.JetFormBulkHandler = {
        init: init,
        getCheckedValues: getCheckedValues,
        updateForm: handleCheckboxChange,
        config: CONFIG
    };
    
    // Auto-initialize
    init();
    
})(jQuery);
