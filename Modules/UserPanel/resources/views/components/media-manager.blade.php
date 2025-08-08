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
          <form @submit.prevent="upload" class="space-y-3">
            <input type="file" x-ref="uploadInput" class="block w-full text-sm" accept="image/*">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
              <i class="fa fa-upload mr-2"></i> Upload
            </button>
          </form>
          <template x-if="uploading">
            <div class="mt-3 text-sm text-gray-600">Uploading...</div>
          </template>
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
        async upload() {
          const file = this.$refs.uploadInput.files[0];
          if (!file) return;
          this.uploading = true;
          const form = new FormData();
          form.append('file', file);
          const res = await fetch(this.storeUrl, { method: 'POST', headers: { 'X-CSRF-TOKEN': this.csrf }, body: form });
          this.uploading = false;
          if (res.ok) {
            const item = await res.json();
            this.tab = 'library';
            this.items.unshift(item);
          } else {
            alert('Upload failed');
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


