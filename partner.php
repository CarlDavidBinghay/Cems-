<section id="partner-section">
  <h1 class="text-3xl font-semibold text-gray-800 mb-6">Partner & Donor Management</h1>

  <!-- Controls -->
  <div class="flex flex-col md:flex-row md:justify-between items-start md:items-center mb-4 gap-4">
    <input type="text" id="partnerSearch" placeholder="Search partners/donors..." class="px-4 py-2 border rounded-lg w-full md:w-1/3 focus:outline-none focus:ring-2 focus:ring-teal-500">
    <button id="addPartnerBtn" class="bg-teal-500 hover:bg-teal-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
      <i class="ri-add-line"></i> Add Partner / Donor
    </button>
  </div>

  <!-- Partner Table -->
  <div class="overflow-x-auto bg-white rounded-2xl shadow">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Roles / Linked Programs</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
        </tr>
      </thead>
      <tbody id="partnerTable" class="bg-white divide-y divide-gray-200"></tbody>
    </table>
  </div>
</section>

<!-- Modal for Add/Edit Partner -->
<div id="partnerModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
  <div class="bg-white rounded-xl p-6 w-full max-w-lg overflow-y-auto max-h-[90vh]">
    <h2 class="text-xl font-semibold mb-4" id="partnerModalTitle">Add Partner / Donor</h2>
    <form id="partnerForm" class="space-y-3">
      <input type="text" id="partnerName" placeholder="Full Name" class="w-full px-4 py-2 border rounded-lg">
      <select id="partnerType" class="w-full px-4 py-2 border rounded-lg">
        <option value="">Select Type</option>
        <option value="Partner">Partner</option>
        <option value="Donor">Donor</option>
      </select>
      <input type="text" id="partnerContact" placeholder="Contact Info" class="w-full px-4 py-2 border rounded-lg">
      <input type="text" id="partnerRoles" placeholder="Roles / Linked Programs" class="w-full px-4 py-2 border rounded-lg">
      <input type="text" id="partnerContributions" placeholder="Financial / In-kind Contributions" class="w-full px-4 py-2 border rounded-lg">
      <div class="flex justify-end gap-3 mt-4">
        <button type="button" id="cancelPartnerBtn" class="px-4 py-2 rounded-lg border hover:bg-gray-100">Cancel</button>
        <button type="submit" class="px-4 py-2 rounded-lg bg-teal-500 text-white hover:bg-teal-600">Save</button>
      </div>
    </form>
  </div>
</div>

<script>
// ==================== Partner & Donor Logic ====================
let partners = [];
let editPartnerIndex = null;

const partnerTable = document.getElementById('partnerTable');
const partnerModal = document.getElementById('partnerModal');
const partnerForm = document.getElementById('partnerForm');
const partnerModalTitle = document.getElementById('partnerModalTitle');
const partnerSearch = document.getElementById('partnerSearch');

function renderPartnerTable(filter = '') {
  partnerTable.innerHTML = '';
  partners
    .filter(p => p.name.toLowerCase().includes(filter.toLowerCase()))
    .forEach((p, index) => {
      const row = document.createElement('tr');
      row.classList.add('hover:bg-gray-50');
      row.innerHTML = `
        <td class="px-6 py-4 whitespace-nowrap">${p.name}</td>
        <td class="px-6 py-4 whitespace-nowrap">${p.type}</td>
        <td class="px-6 py-4 whitespace-nowrap">${p.contact}</td>
        <td class="px-6 py-4 whitespace-nowrap">${p.roles}</td>
        <td class="px-6 py-4 whitespace-nowrap flex gap-2">
          <button class="text-blue-500 hover:text-blue-700" onclick="editPartner(${index})"><i class="ri-edit-line"></i></button>
          <button class="text-red-500 hover:text-red-700" onclick="deletePartner(${index})"><i class="ri-delete-bin-line"></i></button>
        </td>
      `;
      partnerTable.appendChild(row);
    });
}

function openPartnerModal(edit = false) {
  partnerModal.classList.remove('hidden');
  partnerModalTitle.textContent = edit ? 'Edit Partner / Donor' : 'Add Partner / Donor';
}

function closePartnerModal() {
  partnerModal.classList.add('hidden');
  partnerForm.reset();
  editPartnerIndex = null;
}

function editPartner(index) {
  editPartnerIndex = index;
  const p = partners[index];
  document.getElementById('partnerName').value = p.name;
  document.getElementById('partnerType').value = p.type;
  document.getElementById('partnerContact').value = p.contact;
  document.getElementById('partnerRoles').value = p.roles;
  document.getElementById('partnerContributions').value = p.contributions;
  openPartnerModal(true);
}

function deletePartner(index) {
  if(confirm('Are you sure you want to delete this entry?')) {
    partners.splice(index, 1);
    renderPartnerTable(partnerSearch.value);
  }
}

document.getElementById('addPartnerBtn').addEventListener('click', () => openPartnerModal());
document.getElementById('cancelPartnerBtn').addEventListener('click', () => closePartnerModal());

partnerForm.addEventListener('submit', e => {
  e.preventDefault();
  const name = document.getElementById('partnerName').value;
  const type = document.getElementById('partnerType').value;
  const contact = document.getElementById('partnerContact').value;
  const roles = document.getElementById('partnerRoles').value;
  const contributions = document.getElementById('partnerContributions').value;

  if(editPartnerIndex !== null) {
    partners[editPartnerIndex] = {name, type, contact, roles, contributions};
  } else {
    partners.push({name, type, contact, roles, contributions});
  }

  renderPartnerTable(partnerSearch.value);
  closePartnerModal();
});

partnerSearch.addEventListener('input', e => {
  renderPartnerTable(e.target.value);
});

// Initial dummy data
partners.push({name:'ABC Corp', type:'Partner', contact:'abc@example.com', roles:'Program Support', contributions:'In-kind'});
partners.push({name:'John Doe', type:'Donor', contact:'john@example.com', roles:'N/A', contributions:'Financial'});
renderPartnerTable();
</script>