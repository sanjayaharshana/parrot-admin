<?php

namespace Modules\UserPanel\Services\Form;

class DataGrid
{
    protected string $name;
    protected string $label;
    protected ?string $icon;
    protected array $columns = [];
    protected ?string $searchEndpoint = null;
    protected string $addButtonText = 'Add Item';

    public function __construct(string $name, string $label, ?string $icon = null)
    {
        $this->name = $name;
        $this->label = $label;
        $this->icon = $icon;
    }

    public function columns(array $columns): self
    {
        $this->columns = $columns;
        return $this;
    }

    public function searchEndpoint(string $url): self
    {
        $this->searchEndpoint = $url;
        return $this;
    }

    public function addButtonText(string $text): self
    {
        $this->addButtonText = $text;
        return $this;
    }

    public function render(): string
    {
        $cols = $this->columns ?: [
            'id' => ['label' => 'ID'],
            'item_name' => ['label' => 'Item Name'],
            'quantity' => ['label' => 'Qty'],
            'price' => ['label' => 'Price'],
            'total' => ['label' => 'Total'],
        ];

        $columnsJson = htmlspecialchars(json_encode($cols), ENT_QUOTES, 'UTF-8');
        $searchEndpoint = $this->searchEndpoint ? htmlspecialchars($this->searchEndpoint) : '';
        $gridId = 'dg_' . md5($this->name . $this->label);

        $html = '';
        // Alpine controller first to ensure availability before x-data evaluates
        $html .= '<script>\n';
        $html .= 'window.formDataGrid = window.formDataGrid || function(name, columns, endpoint){\n';
        $html .= '  return {\n';
        $html .= '    name, columns, endpoint, rows: [], open: false, search: "", results: [], grandTotal: 0, serialized: "[]",\n';
        $html .= '    currency(v){ return new Intl.NumberFormat(undefined,{style:"currency",currency:"USD"}).format(Number(v||0)); },\n';
        $html .= '    recalc(){ this.grandTotal = this.rows.reduce((s,r)=>s+((Number(r.quantity)||0)*(Number(r.price)||0)),0); this.serialized = JSON.stringify(this.rows); },\n';
        $html .= '    remove(idx){ this.rows.splice(idx,1); this.recalc(); },\n';
        $html .= '    openPicker(){ this.open = true; this.loadResults(); },\n';
        $html .= '    async loadResults(){ if(!this.endpoint) return; const url = this.endpoint + (this.search? (this.endpoint.includes("?")?"&":"?") + "q="+encodeURIComponent(this.search): ""); const res = await fetch(url, { headers: {"X-Requested-With":"XMLHttpRequest"} }); this.results = res.ok ? await res.json() : []; },\n';
        $html .= '    add(item){ const row = { __id: Math.random().toString(36).slice(2), id: item.id, item_name: item.name || item.item_name, quantity: 1, price: Number(item.price)||0, purchase_date: (new Date()).toISOString().slice(0,10) }; this.rows.push(row); this.recalc(); },\n';
        $html .= '  }\n';
        $html .= '}\n';
        $html .= '</script>';

        $html .= '<div class="space-y-2" x-data="formDataGrid(\'' . htmlspecialchars($this->name) . '\', ' . $columnsJson . ', \'' . $searchEndpoint . '\')" x-cloak id="' . $gridId . '">';
        $html .= '<label class="block text-sm font-medium text-gray-700">' . htmlspecialchars($this->label) . '</label>';
        $html .= '<div class="bg-white border rounded-lg overflow-hidden">';
        $html .= '<div class="px-3 py-2 border-b flex items-center justify-between">';
        $html .= '<div class="text-sm text-gray-600">Items</div>';
        $html .= '<button type="button" @click="openPicker()" class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md bg-indigo-600 text-white hover:bg-indigo-700">';
        $html .= '<i class="fa fa-plus mr-2"></i>' . htmlspecialchars($this->addButtonText) . '</button>';
        $html .= '</div>';

        // Table
        $html .= '<div class="overflow-x-auto">';
        $html .= '<table class="min-w-full text-sm">';
        $html .= '<thead class="bg-gray-50">';
        $html .= '<tr>';
        foreach ($cols as $key => $meta) {
            $html .= '<th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">' . htmlspecialchars($meta['label'] ?? $key) . '</th>';
        }
        $html .= '<th class="px-3 py-2"></th>';
        $html .= '</tr></thead>';
        $html .= '<tbody>';
        $html .= '<template x-for="(row, idx) in rows" :key="row.__id">';
        $html .= '<tr class="border-t">';
        foreach ($cols as $key => $meta) {
            if (in_array($key, ['quantity','price'])) {
                $html .= '<td class="px-3 py-2"><input type="number" step="0.01" min="0" class="w-24 px-2 py-1 border rounded" x-model.number="row.' . $key . '" @input="recalc(idx)"></td>';
            } elseif ($key === 'total') {
                $html .= '<td class="px-3 py-2" x-text="currency(row.quantity * row.price)"></td>';
            } else {
                $html .= '<td class="px-3 py-2" x-text="row.' . $key . '"></td>';
            }
        }
        $html .= '<td class="px-3 py-2 text-right"><button type="button" @click="remove(idx)" class="text-rose-600 hover:text-rose-700 text-xs">Remove</button></td>';
        $html .= '</tr>';
        $html .= '</template>';
        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';

        // Footer totals and hidden
        $html .= '<div class="px-3 py-2 border-t flex items-center justify-between">';
        $html .= '<div class="text-xs text-gray-500" x-text="rows.length + \' items\'"></div>';
        $html .= '<div class="text-sm font-medium">Total: <span x-text="currency(grandTotal)"></span></div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<input type="hidden" name="' . htmlspecialchars($this->name) . '" :value="serialized">';

        // Picker modal
        $html .= '<div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">';
        $html .= '<div class="bg-white w-full max-w-2xl rounded-lg shadow-xl overflow-hidden">';
        $html .= '<div class="flex items-center justify-between px-4 py-3 border-b">';
        $html .= '<h3 class="text-sm font-semibold">Select Item</h3>';
        $html .= '<button @click="open=false" class="p-2 text-gray-500 hover:text-gray-700"><i class="fa fa-times"></i></button>';
        $html .= '</div>';
        $html .= '<div class="p-4 space-y-3">';
        $html .= '<div class="flex items-center gap-2"><input type="text" x-model="search" @input.debounce.400ms="loadResults()" placeholder="Search..." class="flex-1 px-3 py-2 border rounded"><button @click="loadResults()" class="px-3 py-2 text-sm bg-gray-100 rounded hover:bg-gray-200">Search</button></div>';
        $html .= '<div class="max-h-80 overflow-auto divide-y">';
        $html .= '<template x-for="item in results" :key="item.id">';
        $html .= '<div class="flex items-center justify-between py-2">';
        $html .= '<div><div class="font-medium text-sm" x-text="item.name || item.item_name"></div><div class="text-xs text-gray-500" x-text="currency(item.price || 0)"></div></div>';
        $html .= '<button class="px-3 py-1.5 text-xs bg-indigo-600 text-white rounded hover:bg-indigo-700" @click="add(item)">Add</button>';
        $html .= '</div>';
        $html .= '</template>';
        $html .= '</div>';
        $html .= '<div class="flex justify-end"><button class="px-3 py-2 text-sm bg-gray-100 rounded hover:bg-gray-200" @click="open=false">Close</button></div>';
        $html .= '</div></div></div>';

        // (script already output above)

        $html .= '</div>';
        return $html;
    }
}


