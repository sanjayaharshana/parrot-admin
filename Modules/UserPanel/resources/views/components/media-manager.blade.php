<div x-data="mediaManager()" x-cloak x-on:media-manager-open.window="openFor($event.detail.targetName)">
  <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white w-full max-w-4xl rounded-lg shadow-xl overflow-hidden">
      <div class="flex items-center justify-between px-4 py-3 border-b">
        <h3 class="text-lg font-semibold">Media Manager</h3>
        <button @click="close()" class="p-2 text-gray-500 hover:text-gray-700">
          <i class="fa fa-times"></i>
        </button>
      </div>

      <div class="px-4 pt-4">
        <div class="flex border-b space-x-6">
          <button :class="tab==='upload' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600'" class="pb-2" @click="tab='upload'">Upload</button>
          <button :class="tab==='library' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600'" class="pb-2" @click="tab='library'; fetchLibrary()">Library</button>
        </div>
      </div>

      <div class="p-4">
        <div x-show="tab==='upload'">
          <div class="space-y-3">
            <div class="relative">
              <div
                x-ref="dropzone"
                @dragover.prevent="dragOver = true"
                @dragleave.prevent="dragOver = false"
                @drop.prevent="handleDrop($event)"
                :class="dragOver ? 'border-blue-400 bg-blue-50' : 'border-gray-300 bg-white'"
                class="flex flex-col items-center justify-center border-2 border-dashed rounded-lg p-6 text-center cursor-pointer">
                <i class="fa fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                <p class="text-sm text-gray-600">Drag & drop images here, or click to select</p>
                <input multiple type="file" x-ref="uploadInput" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*" @change="handleFileSelect($event)">
              </div>
            </div>

            <template x-if="queue.length">
              <div class="space-y-2">
                <template x-for="(item, idx) in queue" :key="item.id">
                  <div class="border rounded-md p-2">
                    <div class="flex items-center justify-between">
                      <div class="flex items-center gap-3">
                        <template x-if="item.preview">
                          <img :src="item.preview" class="w-10 h-10 rounded object-cover border" />
                        </template>
                        <div>
                          <div class="text-sm font-medium" x-text="item.name"></div>
                          <div class="text-xs text-gray-500" x-text="formatSize(item.size)"></div>
                        </div>
                      </div>
                      <div class="text-xs text-gray-500" x-text="item.progress + '%'"></div>
                    </div>
                    <div class="mt-2 bg-gray-200 rounded h-2 overflow-hidden">
                      <div class="bg-blue-600 h-2" :style="'width:' + item.progress + '%' "></div>
                    </div>
                  </div>
                </template>
                <div class="flex justify-end">
                  <button @click="uploadQueue()" :disabled="uploading || !queue.length" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50">
                    Upload
                  </button>
                </div>
              </div>
            </template>
          </div>
        </div>

        <div x-show="tab==='library'">
          <div class="flex items-center justify-between mb-3">
            <input type="text" x-model="search" @input.debounce.400ms="fetchLibrary()" placeholder="Search images..." class="px-3 py-1.5 text-sm border rounded-md">
            <button @click="fetchLibrary()" class="px-3 py-1.5 text-sm bg-gray-100 rounded hover:bg-gray-200">Refresh</button>
          </div>
          <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 max-h-[420px] overflow-auto">
            <template x-for="item in items" :key="item.id">
              <div class="border rounded-md overflow-hidden hover:shadow cursor-pointer group" @click="select(item)">
                <div class="aspect-video bg-gray-50 flex items-center justify-center overflow-hidden">
                  <img :src="item.url" alt="" class="w-full h-full object-cover group-hover:scale-105 transition" />
                </div>
                <div class="px-2 py-1 text-xs truncate" x-text="item.original_name || item.filename"></div>
              </div>
            </template>
          </div>
          <div class="mt-3 flex justify-end">
            <button class="px-3 py-1.5 text-sm bg-gray-100 rounded hover:bg-gray-200" @click="loadMore()" x-show="nextPageUrl">Load more</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    function mediaManager() {
      return {
        open: false,
        tab: 'upload',
        targetName: null,
        items: [],
        nextPageUrl: null,
        search: '',
        uploading: false,
        dragOver: false,
        queue: [],
        indexUrl: '{{ route('media.index') }}',
        storeUrl: '{{ route('media.store') }}',
        csrf: document.head.querySelector('meta[name="csrf-token"]').content,
        openFor(targetName) { this.open = true; this.tab = 'upload'; this.targetName = targetName; this.items=[]; this.nextPageUrl=null; },
        close() { this.open = false; this.targetName = null; },
        async fetchLibrary(url = null) {
          const u = url || (this.indexUrl + '?' + new URLSearchParams({ search: this.search }).toString());
          const res = await fetch(u, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
          const data = await res.json();
          this.items = (url ? this.items.concat(data.data) : data.data);
          this.nextPageUrl = data.next_page_url;
        },
        async loadMore() { if (this.nextPageUrl) { await this.fetchLibrary(this.nextPageUrl); } },
        handleFileSelect(e) {
          const files = Array.from(e.target.files || []);
          this.addToQueue(files);
        },
        handleDrop(e) {
          this.dragOver = false;
          const files = Array.from(e.dataTransfer.files || []);
          this.addToQueue(files);
        },
        addToQueue(files) {
          const accepted = files.filter(f => /^image\//.test(f.type));
          for (const file of accepted) {
            const id = Math.random().toString(36).slice(2);
            const item = { id, file, name: file.name, size: file.size, progress: 0, preview: '' };
            try {
              item.preview = URL.createObjectURL(file);
            } catch (e) {}
            this.queue.push(item);
          }
        },
        formatSize(bytes) {
          if (bytes < 1024) return bytes + ' B';
          if (bytes < 1024*1024) return (bytes/1024).toFixed(1) + ' KB';
          return (bytes/1024/1024).toFixed(1) + ' MB';
        },
        async uploadQueue() {
          if (!this.queue.length) return;
          this.uploading = true;
          try {
            const form = new FormData();
            for (const item of this.queue) form.append('files[]', item.file);
            const res = await fetch(this.storeUrl, {
              method: 'POST',
              headers: { 'X-CSRF-TOKEN': this.csrf },
              body: form,
            });
            if (!res.ok) throw new Error('Upload failed');
            const data = await res.json();
            const created = Array.isArray(data.data) ? data.data : [data];
            // Simulate full progress for now
            this.queue.forEach(it => it.progress = 100);
            // Push into library
            for (const m of created) this.items.unshift(m);
            this.queue = [];
            this.tab = 'library';
          } catch (e) {
            alert(e.message || 'Upload failed');
          } finally {
            this.uploading = false;
          }
        },
        select(item) {
          if (!this.targetName) return;
          // Fill hidden input `${name}_media_id` and preview `${name}_preview`
          const hidden = document.querySelector(`[data-media-hidden="${this.targetName}"]`);
          if (hidden) hidden.value = item.id;
          const preview = document.querySelector(`[data-media-preview="${this.targetName}"]`);
          if (preview) { preview.src = item.url; preview.classList.remove('hidden'); }
          const label = document.querySelector(`[data-media-filename="${this.targetName}"]`);
          if (label) label.textContent = item.original_name || item.filename;
          this.close();
        }
      }
    }

    window.openMediaManager = function(targetName) {
      window.dispatchEvent(new CustomEvent('media-manager-open', { detail: { targetName } }));
    }
  </script>
</div>


