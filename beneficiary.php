<section id="beneficiary-section">
    <h1 class="text-3xl font-semibold text-gray-800 mb-6">Beneficiary Management</h1>

    <!-- Controls -->
    <div class="flex flex-col md:flex-row md:justify-between items-start md:items-center mb-4 gap-4">
        <input type="text" id="searchInput" placeholder="Search beneficiaries..." class="px-4 py-2 border rounded-lg w-full md:w-1/3 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button id="addBtn" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
            <i class="ri-add-line"></i> Add Beneficiary
        </button>
    </div>

    <!-- Beneficiary Table -->
    <div class="overflow-x-auto bg-white rounded-2xl shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Age</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gender</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Occupation</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody id="beneficiaryTable" class="bg-white divide-y divide-gray-200">
                <!-- Rows dynamically inserted here -->
            </tbody>
        </table>
    </div>
</section>

<!-- Modal for Add/Edit Beneficiary -->
<div id="modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-xl p-6 w-full max-w-md">
        <h2 class="text-xl font-semibold mb-4" id="modalTitle">Add Beneficiary</h2>
        <form id="beneficiaryForm" class="space-y-3">
            <input type="text" id="name" placeholder="Full Name" class="w-full px-4 py-2 border rounded-lg">
            <input type="number" id="age" placeholder="Age" class="w-full px-4 py-2 border rounded-lg">
            <select id="gender" class="w-full px-4 py-2 border rounded-lg">
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
            <input type="text" id="occupation" placeholder="Occupation" class="w-full px-4 py-2 border rounded-lg">
            <div class="flex justify-end gap-3 mt-4">
                <button type="button" id="cancelBtn" class="px-4 py-2 rounded-lg border hover:bg-gray-100">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-blue-500 text-white hover:bg-blue-600">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
// Beneficiary Management Logic
let beneficiaries = [];
let editIndex = null;

const tableBody = document.getElementById('beneficiaryTable');
const modal = document.getElementById('modal');
const form = document.getElementById('beneficiaryForm');
const modalTitle = document.getElementById('modalTitle');
const searchInput = document.getElementById('searchInput');

function renderTable(filter = '') {
    tableBody.innerHTML = '';
    beneficiaries
        .filter(b => b.name.toLowerCase().includes(filter.toLowerCase()))
        .forEach((b, index) => {
        const row = document.createElement('tr');
        row.classList.add('hover:bg-gray-50');
        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap">${b.name}</td>
            <td class="px-6 py-4 whitespace-nowrap">${b.age}</td>
            <td class="px-6 py-4 whitespace-nowrap">${b.gender}</td>
            <td class="px-6 py-4 whitespace-nowrap">${b.occupation}</td>
            <td class="px-6 py-4 whitespace-nowrap flex gap-2">
                <button class="text-blue-500 hover:text-blue-700" onclick="editBeneficiary(${index})"><i class="ri-edit-line"></i></button>
                <button class="text-red-500 hover:text-red-700" onclick="deleteBeneficiary(${index})"><i class="ri-delete-bin-line"></i></button>
            </td>
        `;
        tableBody.appendChild(row);
    });
}

function openModal(edit = false) {
    modal.classList.remove('hidden');
    modalTitle.textContent = edit ? 'Edit Beneficiary' : 'Add Beneficiary';
}

function closeModal() {
    modal.classList.add('hidden');
    form.reset();
    editIndex = null;
}

function editBeneficiary(index) {
    editIndex = index;
    const b = beneficiaries[index];
    document.getElementById('name').value = b.name;
    document.getElementById('age').value = b.age;
    document.getElementById('gender').value = b.gender;
    document.getElementById('occupation').value = b.occupation;
    openModal(true);
}

function deleteBeneficiary(index) {
    if(confirm('Are you sure you want to delete this beneficiary?')) {
        beneficiaries.splice(index, 1);
        renderTable(searchInput.value);
    }
}

document.getElementById('addBtn').addEventListener('click', () => openModal());
document.getElementById('cancelBtn').addEventListener('click', () => closeModal());

form.addEventListener('submit', e => {
    e.preventDefault();
    const name = document.getElementById('name').value;
    const age = document.getElementById('age').value;
    const gender = document.getElementById('gender').value;
    const occupation = document.getElementById('occupation').value;

    if(editIndex !== null) {
        beneficiaries[editIndex] = {name, age, gender, occupation};
    } else {
        beneficiaries.push({name, age, gender, occupation});
    }

    renderTable(searchInput.value);
    closeModal();
});

searchInput.addEventListener('input', e => {
    renderTable(e.target.value);
});

// Initial dummy data
beneficiaries.push({name:'Juan Dela Cruz', age:30, gender:'Male', occupation:'Farmer'});
beneficiaries.push({name:'Maria Santos', age:25, gender:'Female', occupation:'Teacher'});
renderTable();
</script>