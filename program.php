<section id="program-section">
    <h1 class="text-3xl font-semibold text-gray-800 mb-6">Program Management</h1>

    <!-- Controls -->
    <div class="flex flex-col md:flex-row md:justify-between items-start md:items-center mb-4 gap-4">
        <input type="text" id="programSearch" placeholder="Search programs..." class="px-4 py-2 border rounded-lg w-full md:w-1/3 focus:outline-none focus:ring-2 focus:ring-yellow-500">
        <button id="addProgramBtn" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
            <i class="ri-add-line"></i> Add Program
        </button>
    </div>

    <!-- Program Table -->
    <div class="overflow-x-auto bg-white rounded-2xl shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Goals</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duration</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Budget</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody id="programTable" class="bg-white divide-y divide-gray-200"></tbody>
        </table>
    </div>
</section>

<!-- Modal for Add/Edit Program -->
<div id="programModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-xl p-6 w-full max-w-lg">
        <h2 class="text-xl font-semibold mb-4" id="programModalTitle">Add Program</h2>
        <form id="programForm" class="space-y-3">
            <input type="text" id="programName" placeholder="Program Name" class="w-full px-4 py-2 border rounded-lg">
            <textarea id="programDesc" placeholder="Description" class="w-full px-4 py-2 border rounded-lg"></textarea>
            <input type="text" id="programGoals" placeholder="Goals & Objectives" class="w-full px-4 py-2 border rounded-lg">
            <input type="text" id="programBeneficiaries" placeholder="Target Beneficiaries" class="w-full px-4 py-2 border rounded-lg">
            <input type="text" id="programDuration" placeholder="Duration (e.g., Jan 2025 - Dec 2025)" class="w-full px-4 py-2 border rounded-lg">
            <input type="number" id="programBudget" placeholder="Budget" class="w-full px-4 py-2 border rounded-lg">
            <input type="file" id="programDocs" class="w-full py-2">
            <div class="flex justify-end gap-3 mt-4">
                <button type="button" id="cancelProgramBtn" class="px-4 py-2 rounded-lg border hover:bg-gray-100">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-yellow-500 text-white hover:bg-yellow-600">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
// Program Management Logic
let programs = [];
let programEditIndex = null;

const programTable = document.getElementById('programTable');
const programModal = document.getElementById('programModal');
const programForm = document.getElementById('programForm');
const programModalTitle = document.getElementById('programModalTitle');
const programSearch = document.getElementById('programSearch');

function renderPrograms(filter='') {
    programTable.innerHTML = '';
    programs
        .filter(p => p.name.toLowerCase().includes(filter.toLowerCase()))
        .forEach((p, i) => {
            const row = document.createElement('tr');
            row.classList.add('hover:bg-gray-50');
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap">${p.name}</td>
                <td class="px-6 py-4 whitespace-nowrap">${p.goals}</td>
                <td class="px-6 py-4 whitespace-nowrap">${p.duration}</td>
                <td class="px-6 py-4 whitespace-nowrap">${p.budget}</td>
                <td class="px-6 py-4 whitespace-nowrap flex gap-2">
                    <button class="text-yellow-500 hover:text-yellow-700" onclick="editProgram(${i})"><i class="ri-edit-line"></i></button>
                    <button class="text-red-500 hover:text-red-700" onclick="deleteProgram(${i})"><i class="ri-delete-bin-line"></i></button>
                </td>
            `;
            programTable.appendChild(row);
        });
}

function openProgramModal(edit=false) {
    programModal.classList.remove('hidden');
    programModalTitle.textContent = edit ? 'Edit Program' : 'Add Program';
}

function closeProgramModal() {
    programModal.classList.add('hidden');
    programForm.reset();
    programEditIndex = null;
}

function editProgram(index) {
    programEditIndex = index;
    const p = programs[index];
    document.getElementById('programName').value = p.name;
    document.getElementById('programDesc').value = p.desc;
    document.getElementById('programGoals').value = p.goals;
    document.getElementById('programBeneficiaries').value = p.beneficiaries;
    document.getElementById('programDuration').value = p.duration;
    document.getElementById('programBudget').value = p.budget;
    openProgramModal(true);
}

function deleteProgram(index) {
    if(confirm('Are you sure you want to delete this program?')) {
        programs.splice(index, 1);
        renderPrograms(programSearch.value);
    }
}

// Event Listeners
document.getElementById('addProgramBtn').addEventListener('click', () => openProgramModal());
document.getElementById('cancelProgramBtn').addEventListener('click', () => closeProgramModal());

programForm.addEventListener('submit', e => {
    e.preventDefault();
    const name = document.getElementById('programName').value;
    const desc = document.getElementById('programDesc').value;
    const goals = document.getElementById('programGoals').value;
    const beneficiaries = document.getElementById('programBeneficiaries').value;
    const duration = document.getElementById('programDuration').value;
    const budget = document.getElementById('programBudget').value;
    const docs = document.getElementById('programDocs').files;

    const programData = {name, desc, goals, beneficiaries, duration, budget, docs};

    if(programEditIndex !== null) {
        programs[programEditIndex] = programData;
    } else {
        programs.push(programData);
    }

    renderPrograms(programSearch.value);
    closeProgramModal();
});

programSearch.addEventListener('input', e => renderPrograms(e.target.value));

// Optional: Add some dummy data
programs.push({name:'Health Program', desc:'Health & Wellness', goals:'Increase awareness', beneficiaries:'Community', duration:'Jan-Dec 2025', budget:100000, docs:[]});
renderPrograms();
</script>