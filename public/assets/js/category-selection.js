/**
 * Category and Sub-category Dynamic Selection
 */

document.addEventListener('DOMContentLoaded', function () {
    const mainCategorySelect = document.getElementById('mainCategorySelect');
    const subCategorySelect = document.getElementById('subCategorySelect');
    const subCategoryWrapper = document.getElementById('subCategoryDropdownWrapper');
    const selectedCategoryIdInput = document.getElementById('selected_category_id');
    const categoryTree = window.categoryTree || [];

    if (!mainCategorySelect) return;

    // 1. Handle Main Category Change
    mainCategorySelect.addEventListener('change', function () {
        const parentId = this.value;
        const parent = categoryTree.find(c => c.id == parentId);

        if (parent && parent.children && parent.children.length > 0) {
            populateSubCategories(parent.children);
            subCategoryWrapper.style.display = 'block';

            // Reset hidden input until sub-category is selected
            selectedCategoryIdInput.value = '';
            triggerChange(selectedCategoryIdInput);
        } else {
            subCategoryWrapper.style.display = 'none';
            selectedCategoryIdInput.value = parentId;
            triggerChange(selectedCategoryIdInput);
        }
    });

    // 2. Handle Sub Category Change
    if (subCategorySelect) {
        subCategorySelect.addEventListener('change', function () {
            selectedCategoryIdInput.value = this.value;
            triggerChange(selectedCategoryIdInput);
        });
    }

    /**
     * Helper to populate sub-category dropdown
     */
    function populateSubCategories(children) {
        subCategorySelect.innerHTML = '<option value="" disabled selected>Choose Sub-Category</option>';

        children.forEach(child => {
            const option = document.createElement('option');
            option.value = child.id;
            option.textContent = child.name;
            if (selectedCategoryIdInput.value == child.id) {
                option.selected = true;
            }
            subCategorySelect.appendChild(option);
        });
    }

    /**
     * Cross-browser change trigger
     */
    function triggerChange(element) {
        element.dispatchEvent(new Event('change', { bubbles: true }));
        if (typeof jQuery !== 'undefined') {
            jQuery(element).trigger('change');
        }
    }

    /**
     * Initialize state on page load (for edit pages or validation errors)
     */
    const initialId = selectedCategoryIdInput.value;
    if (initialId) {
        let activeParent = null;
        let activeChild = null;

        categoryTree.forEach(parent => {
            if (parent.id == initialId) {
                activeParent = parent;
            } else if (parent.children) {
                const child = parent.children.find(c => c.id == initialId);
                if (child) {
                    activeParent = parent;
                    activeChild = child;
                }
            }
        });

        if (activeParent) {
            mainCategorySelect.value = activeParent.id;
            if (activeParent.children && activeParent.children.length > 0) {
                populateSubCategories(activeParent.children);
                subCategoryWrapper.style.display = 'block';
                if (activeChild) {
                    subCategorySelect.value = activeChild.id;
                }
                // Ensure the hidden input maintains its initial value during setup
                selectedCategoryIdInput.value = initialId;
            } else {
                subCategoryWrapper.style.display = 'none';
                selectedCategoryIdInput.value = activeParent.id;
            }
        }
    }
});
