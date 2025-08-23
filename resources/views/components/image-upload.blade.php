@props(['name', 'label' => 'Upload Gambar', 'multiple' => false, 'required' => false, 'accept' => 'image/*', 'maxFiles' => 5])

<div class="image-upload-component" x-data="imageUpload()">
    <label class="form-label">{{ $label }}</label>
    
    <div class="upload-area border-2 border-dashed border-secondary rounded p-4 text-center mb-3"
         :class="{ 'border-primary': isDragOver }"
         @dragover.prevent="isDragOver = true"
         @dragleave.prevent="isDragOver = false"
         @drop.prevent="handleDrop($event)">
        
        <div class="upload-icon mb-3">
            <i class="fas fa-cloud-upload-alt fa-3x text-muted"></i>
        </div>
        
        <p class="mb-2">Drag & drop gambar di sini atau</p>
        <button type="button" 
                class="btn btn-outline-primary"
                @click="$refs.fileInput.click()">
            <i class="fas fa-folder-open me-2"></i>Pilih File
        </button>
        
        <input type="file" 
               x-ref="fileInput"
               name="{{ $name }}{{ $multiple ? '[]' : '' }}"
               {{ $multiple ? 'multiple' : '' }}
               {{ $required ? 'required' : '' }}
               accept="{{ $accept }}"
               class="d-none"
               @change="handleFileSelect($event)">
        
        <p class="text-muted small mt-2">
            Format yang didukung: JPG, PNG, GIF. Maksimal {{ $maxFiles }} file.
        </p>
    </div>
    
    <!-- Preview Images -->
    <div x-show="previewImages.length > 0" class="preview-images mb-3">
        <h6 class="mb-2">Preview Gambar:</h6>
        <div class="row g-2">
            <template x-for="(image, index) in previewImages" :key="index">
                <div class="col-auto">
                    <div class="position-relative">
                        <img :src="image.preview" 
                             class="img-thumbnail" 
                             style="width: 100px; height: 100px; object-fit: cover;">
                        <button type="button" 
                                class="btn btn-danger btn-sm position-absolute top-0 end-0"
                                style="transform: translate(50%, -50%);"
                                @click="removeImage(index)">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>
    
    @error($name)
        <div class="invalid-feedback d-block">
            {{ $message }}
        </div>
    @enderror
</div>

<script>
function imageUpload() {
    return {
        isDragOver: false,
        previewImages: [],
        maxFiles: {{ $maxFiles }},
        
        handleDrop(event) {
            this.isDragOver = false;
            const files = event.dataTransfer.files;
            this.processFiles(files);
        },
        
        handleFileSelect(event) {
            const files = event.target.files;
            this.processFiles(files);
        },
        
        processFiles(files) {
            if (this.previewImages.length + files.length > this.maxFiles) {
                alert(`Maksimal ${this.maxFiles} file yang dapat diupload`);
                return;
            }
            
            Array.from(files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.previewImages.push({
                            file: file,
                            preview: e.target.result
                        });
                    };
                    reader.readAsDataURL(file);
                }
            });
        },
        
        removeImage(index) {
            this.previewImages.splice(index, 1);
        }
    }
}
</script>

<style>
.upload-area {
    transition: all 0.3s ease;
    cursor: pointer;
}

.upload-area:hover {
    border-color: #0d6efd !important;
    background-color: rgba(13, 110, 253, 0.05);
}

.upload-area.border-primary {
    background-color: rgba(13, 110, 253, 0.1);
}

.preview-images img {
    transition: transform 0.2s ease;
}

.preview-images img:hover {
    transform: scale(1.05);
}
</style>
