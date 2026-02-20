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
