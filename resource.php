<section id="resource-section">
  <h1 class="text-3xl font-semibold text-gray-800 mb-6">Resource Management</h1>

  <!-- Controls -->
  <div class="flex flex-col md:flex-row md:justify-between items-start md:items-center mb-4 gap-4">
    <input type="text" id="resourceSearch" placeholder="Search resources..." class="px-4 py-2 border rounded-lg w-full md:w-1/3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
    <button id="addResourceBtn" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
      <i class="ri-add-line"></i> Add Resource
    </button>
  </div>

  <!-- Resource Table -->
  <div class="overflow-x-auto bg-white rounded-2xl shadow">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Resource Name</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity / Availability</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
        </tr>
      </thead>
      <tbody id="resourceTable" class="bg-white divide-y divide-gray-200"></tbody>
    </table>
  </div>
</section>

<!-- Modal for Add/Edit Resource -->
<div id="resourceModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
  <div class="bg-white rounded-xl p-6 w-full max-w-lg overflow-y-auto max-h-[90vh]">
    <h2 class="text-xl font-semibold mb-4" id="resourceModalTitle">Add Resource</h2>
    <form id="resourceForm" class="space-y-3">
      <input type="text" id="resourceName" placeholder="Resource Name" class="w-full px-4 py-2 border rounded-lg">
      <input type="text" id="resourceType" placeholder="Type (equipment, material, facility)" class="w-full px-4 py-2 border rounded-lg">
      <input type="number" id="resourceQuantity" placeholder="Quantity / Availability" class="w-full px-4 py-2 border rounded-lg">
      <input type="text" id="resourceLocation" placeholder="Location" class="w-full px-4 py-2 border rounded-lg">
      <div class="flex justify-end gap-3 mt-4">
        <button type="button" id="cancelResourceBtn" class="px-4 py-2 rounded-lg border hover:bg-gray-100">Cancel</button>
        <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-500 text-white hover:bg-indigo-600">Save</button>
      </div>
    </form>
  </div>
</div>

<script>
// ==================== Resource Management Logic ====================
let resources = [];
let editResourceIndex = null;

const resourceTable = document.getElementById('resourceTable');
const resourceModal = document.getElementById('resourceModal');
const resourceForm = document.getElementById('resourceForm');
const resourceModalTitle = document.getElementById('resourceModalTitle');
const resourceSearch = document.getElementById('resourceSearch');

function renderResourceTable(filter = '') {
  resourceTable.innerHTML = '';
  resources
    .filter(r => r.name.toLowerCase().includes(filter.toLowerCase()))
    .forEach((r, index) => {
      const row = document.createElement('tr');
      row.classList.add('hover:bg-gray-50');
      row.innerHTML = `
        <td class="px-6 py-4 whitespace-nowrap">${r.name}</td>
        <td class="px-6 py-4 whitespace-nowrap">${r.type}</td>
        <td class="px-6 py-4 whitespace-nowrap">${r.quantity}</td>
        <td class="px-6 py-4 whitespace-nowrap">${r.location}</td>
        <td class="px-6 py-4 whitespace-nowrap flex gap-2">
          <button class="text-blue-500 hover:text-blue-700" onclick="editResource(${index})"><i class="ri-edit-line"></i></button>
          <button class="text-red-500 hover:text-red-700" onclick="deleteResource(${index})"><i class="ri-delete-bin-line"></i></button>
        </td>
      `;
      resourceTable.appendChild(row);
    });
}

function openResourceModal(edit = false) {
  resourceModal.classList.remove('hidden');
  resourceModalTitle.textContent = edit ? 'Edit Resource' : 'Add Resource';
}

function closeResourceModal() {
  resourceModal.classList.add('hidden');
  resourceForm.reset();
  editResourceIndex = null;
}

function editResource(index) {
  editResourceIndex = index;
  const r = resources[index];
  document.getElementById('resourceName').value = r.name;
  document.getElementById('resourceType').value = r.type;
  document.getElementById('resourceQuantity').value = r.quantity;
  document.getElementById('resourceLocation').value = r.location;
  openResourceModal(true);
}

function deleteResource(index) {
  if(confirm('Are you sure you want to delete this resource?')) {
    resources.splice(index, 1);
    renderResourceTable(resourceSearch.value);
  }
}

document.getElementById('addResourceBtn').addEventListener('click', () => openResourceModal());
document.getElementById('cancelResourceBtn').addEventListener('click', () => closeResourceModal());

resourceForm.addEventListener('submit', e => {
  e.preventDefault();
  const name = document.getElementById('resourceName').value;
  const type = document.getElementById('resourceType').value;
  const quantity = document.getElementById('resourceQuantity').value;
  const location = document.getElementById('resourceLocation').value;

  if(editResourceIndex !== null) {
    resources[editResourceIndex] = {name, type, quantity, location};
  } else {
    resources.push({name, type, quantity, location});
  }

  renderResourceTable(resourceSearch.value);
  closeResourceModal();
});

resourceSearch.addEventListener('input', e => renderResourceTable(e.target.value));

// Initial dummy data
resources.push({name:'Projector', type:'Equipment', quantity:5, location:'Conference Room'});
resources.push({name:'Tables', type:'Facility', quantity:10, location:'Main Hall'});
renderResourceTable();
</script>