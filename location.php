<section id="location-section">
  <h1 class="text-3xl font-semibold text-gray-800 mb-6">Location Management</h1>

  <!-- Controls -->
  <div class="flex flex-col md:flex-row md:justify-between items-start md:items-center mb-4 gap-4">
    <input type="text" id="locationSearch" placeholder="Search locations..." class="px-4 py-2 border rounded-lg w-full md:w-1/3 focus:outline-none focus:ring-2 focus:ring-orange-500">
    <button id="addLocationBtn" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
      <i class="ri-add-line"></i> Add Location
    </button>
  </div>

  <!-- Location Table -->
  <div class="overflow-x-auto bg-white rounded-2xl shadow">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Location Name</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Address</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Facilities</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
        </tr>
      </thead>
      <tbody id="locationTable" class="bg-white divide-y divide-gray-200"></tbody>
    </table>
  </div>
</section>

<!-- Modal for Add/Edit Location -->
<div id="locationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
  <div class="bg-white rounded-xl p-6 w-full max-w-lg overflow-y-auto max-h-[90vh]">
    <h2 class="text-xl font-semibold mb-4" id="locationModalTitle">Add Location</h2>
    <form id="locationForm" class="space-y-3">
      <input type="text" id="locationName" placeholder="Location Name" class="w-full px-4 py-2 border rounded-lg">
      <input type="text" id="locationAddress" placeholder="Address" class="w-full px-4 py-2 border rounded-lg">
      <input type="text" id="locationFacilities" placeholder="Facilities (comma-separated)" class="w-full px-4 py-2 border rounded-lg">
      <div class="flex justify-end gap-3 mt-4">
        <button type="button" id="cancelLocationBtn" class="px-4 py-2 rounded-lg border hover:bg-gray-100">Cancel</button>
        <button type="submit" class="px-4 py-2 rounded-lg bg-orange-500 text-white hover:bg-orange-600">Save</button>
      </div>
    </form>
  </div>
</div>

<script>
// ==================== Location Management Logic ====================
let locations = [];
let editLocationIndex = null;

const locationTable = document.getElementById('locationTable');
const locationModal = document.getElementById('locationModal');
const locationForm = document.getElementById('locationForm');
const locationModalTitle = document.getElementById('locationModalTitle');
const locationSearch = document.getElementById('locationSearch');

function renderLocationTable(filter = '') {
  locationTable.innerHTML = '';
  locations
    .filter(l => l.name.toLowerCase().includes(filter.toLowerCase()))
    .forEach((l, index) => {
      const row = document.createElement('tr');
      row.classList.add('hover:bg-gray-50');
      row.innerHTML = `
        <td class="px-6 py-4 whitespace-nowrap">${l.name}</td>
        <td class="px-6 py-4 whitespace-nowrap">${l.address}</td>
        <td class="px-6 py-4 whitespace-nowrap">${l.facilities}</td>
        <td class="px-6 py-4 whitespace-nowrap flex gap-2">
          <button class="text-blue-500 hover:text-blue-700" onclick="editLocation(${index})"><i class="ri-edit-line"></i></button>
          <button class="text-red-500 hover:text-red-700" onclick="deleteLocation(${index})"><i class="ri-delete-bin-line"></i></button>
        </td>
      `;
      locationTable.appendChild(row);
    });
}

function openLocationModal(edit = false) {
  locationModal.classList.remove('hidden');
  locationModalTitle.textContent = edit ? 'Edit Location' : 'Add Location';
}

function closeLocationModal() {
  locationModal.classList.add('hidden');
  locationForm.reset();
  editLocationIndex = null;
}

function editLocation(index) {
  editLocationIndex = index;
  const l = locations[index];
  document.getElementById('locationName').value = l.name;
  document.getElementById('locationAddress').value = l.address;
  document.getElementById('locationFacilities').value = l.facilities;
  openLocationModal(true);
}

function deleteLocation(index) {
  if(confirm('Are you sure you want to delete this location?')) {
    locations.splice(index, 1);
    renderLocationTable(locationSearch.value);
  }
}

document.getElementById('addLocationBtn').addEventListener('click', () => openLocationModal());
document.getElementById('cancelLocationBtn').addEventListener('click', () => closeLocationModal());

locationForm.addEventListener('submit', e => {
  e.preventDefault();
  const name = document.getElementById('locationName').value;
  const address = document.getElementById('locationAddress').value;
  const facilities = document.getElementById('locationFacilities').value;

  if(editLocationIndex !== null) {
    locations[editLocationIndex] = {name, address, facilities};
  } else {
    locations.push({name, address, facilities});
  }

  renderLocationTable(locationSearch.value);
  closeLocationModal();
});

locationSearch.addEventListener('input', e => renderLocationTable(e.target.value));

// Initial dummy data
locations.push({name:'Community Hall', address:'123 Main St', facilities:'Stage, Chairs, Sound System'});
locations.push({name:'Training Center', address:'45 West Ave', facilities:'Projector, Tables, Whiteboard'});
renderLocationTable();
</script>