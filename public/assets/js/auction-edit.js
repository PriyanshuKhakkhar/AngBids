/**
 * Auction Edit Page Logic
 */

let existingImageCount = 0;
const maxTotalImages = 5;

function initAuctionEdit(config) {
    existingImageCount = config.existingImageCount;

    // 1. Initialize Image Upload Manager
    if (typeof ImageUploadManager !== 'undefined') {
        window.imageUploadManager = new ImageUploadManager({
            inputSelector: '#imageInput',
            gridSelector: '#imagePreviewGrid',
            primaryInputSelector: '#primaryImageIndex',
            maxFiles: 5,
            existingCount: existingImageCount
        });
    }

    // 2. Initialize Form Validator
    if (typeof AuctionFormValidator !== 'undefined') {
        new AuctionFormValidator('auctionEditForm');
    }
}

function removeExistingImage(imageId) {
    if (!confirm('Are you sure you want to remove this image?')) return;

    const container = document.getElementById(`existing-img-${imageId}`);
    if (container) {
        container.remove();

        // Add to hidden deleted_images input
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'deleted_images[]';
        input.value = imageId;

        const targetContainer = document.getElementById('deletedImagesContainer');
        if (targetContainer) {
            targetContainer.appendChild(input);
        }

        existingImageCount--;
        updateImageLimitUI();

        // Notify ImageUploadManager
        if (window.imageUploadManager) {
            window.imageUploadManager.setExistingCount(existingImageCount);
        }
    }
}

function updateImageLimitUI() {
    const info = document.getElementById('imageLimitInfo');
    if (info) {
        const remaining = maxTotalImages - existingImageCount;
        info.textContent = `You have ${existingImageCount} images. You can add ${Math.max(0, remaining)} more.`;
    }
}

// Dynamic Category Fields Logic for Edit Page
function initDynamicFields() {
    const categorySelect = document.querySelector('[name="category_id"]');
    const dynamicFieldsContainer = document.getElementById('dynamicFieldsContainer');
    const categoryGroups = document.querySelectorAll('.category-fields-group');

    if (categorySelect && dynamicFieldsContainer) {
        categorySelect.onchange = function () {
            const categoryId = this.value;

            // Hide all groups first
            categoryGroups.forEach(group => group.classList.add('d-none'));
            dynamicFieldsContainer.classList.add('d-none');

            // If no category is selected, just stop here
            if (!categoryId || categoryId === "" || !window.categoryTree) return;

            // Find category or its parent's slug
            let targetSlug = null;
            window.categoryTree.forEach(parent => {
                if (parent.id == categoryId) {
                    targetSlug = parent.slug;
                } else if (parent.children) {
                    const child = parent.children.find(c => c.id == categoryId);
                    if (child) {
                        targetSlug = parent.slug; // Use parent slug for specs
                    }
                }
            });

            if (!targetSlug) return;

            // Show relevant group
            const targetGroup = document.getElementById(`category_fields_${targetSlug}`);
            if (targetGroup) {
                dynamicFieldsContainer.classList.remove('d-none');
                targetGroup.classList.remove('d-none');
            }
        };

        // Trigger change on load if category already selected
        if (categorySelect.value && categorySelect.value !== "") {
            // Use a small timeout to ensure categoryTree is ready and other scripts finished
            setTimeout(() => {
                categorySelect.onchange();
            }, 100);
        }
    }
}

// Add to init function
const originalInitAuctionEdit = initAuctionEdit;
initAuctionEdit = function (config) {
    originalInitAuctionEdit(config);
    initDynamicFields();
};
