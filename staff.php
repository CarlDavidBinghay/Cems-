<section id="staff-section">
  <h1 class="text-3xl font-semibold text-gray-800 mb-6">Coordinator / Staff / Volunteers</h1>

  <!-- Controls -->
  <div class="flex flex-col md:flex-row md:justify-between items-start md:items-center mb-4 gap-4">
    <input type="text" id="staffSearch" placeholder="Search staff/volunteers..." class="px-4 py-2 border rounded-lg w-full md:w-1/3 focus:outline-none focus:ring-2 focus:ring-cyan-500">
    <button id="addStaffBtn" class="bg-cyan-500 hover:bg-cyan-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
      <i class="ri-add-line"></i> Add Staff / Volunteer
    </button>
  </div>

  <!-- Staff Table -->
  <div class="overflow-x-auto bg-white rounded-2xl shadow">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assigned Programs/Projects</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Performance Metrics</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Training / Certifications</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
        </tr>
      </thead>
      <tbody id="staffTable" class="bg-white divide-y divide-gray-200"></tbody>
    </table>
  </div>
</section>

<!-- Modal for Add/Edit Staff -->
<div id="staffModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
  <div class="bg-white rounded-xl p-6 w-full max-w-lg overflow-y-auto max-h-[90vh]">
    <h2 class="text-xl font-semibold mb-4" id="staffModalTitle">Add Staff / Volunteer</h2>
    <form id="staffForm" class="space-y-3">
      <input type="text" id="staffName" placeholder="Full Name" class="w-full px-4 py-2 border rounded-lg">
      
      <!-- Role Dropdown (Admin only) -->
      <div id="staffRoleContainer">
        <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
        <select id="staffRole" class="w-full px-4 py-2 border rounded-lg">
          <option value="">Select Role</option>
          <option value="Admin">Admin</option>
          <option value="Coordinator">Coordinator</option>
          <option value="Staff">Staff</option>
          <option value="Volunteer">Volunteer</option>
        </select>
      </div>
      
      <input type="text" id="staffAssignments" placeholder="Assigned Programs/Projects (comma-separated)" class="w-full px-4 py-2 border rounded-lg">
      <input type="text" id="staffMetrics" placeholder="Performance Metrics" class="w-full px-4 py-2 border rounded-lg">
      <input type="text" id="staffTraining" placeholder="Training / Certifications" class="w-full px-4 py-2 border rounded-lg">
      <div class="flex justify-end gap-3 mt-4">
        <button type="button" id="cancelStaffBtn" class="px-4 py-2 rounded-lg border hover:bg-gray-100">Cancel</button>
        <button type="submit" class="px-4 py-2 rounded-lg bg-cyan-500 text-white hover:bg-cyan-600">Save</button>
      </div>
    </form>
  </div>
</div>

<script>

// ==================== Staff / Volunteer Management Logic ====================
let staffList = [];
let editStaffIndex = null;

const staffTable = document.getElementById('staffTable');
const staffModal = document.getElementById('staffModal');
const staffForm = document.getElementById('staffForm');
const staffModalTitle = document.getElementById('staffModalTitle');
const staffSearch = document.getElementById('staffSearch');

// Check if current user is admin
function isAdmin() {
  return true; // TODO: change to real session check
}

function updateRoleFieldVisibility() {
  const roleContainer = document.getElementById('staffRoleContainer');
  const roleSelect = document.getElementById('staffRole');
  
  if (isAdmin()) {
    roleContainer.classList.remove('hidden');
    roleSelect.disabled = false;
  } else {
    roleContainer.classList.add('hidden');
    roleSelect.disabled = true;
  }
}

// Load staff from API
fetch("/cems/api/staff.api.php?action=get_staff")
  .then(res => res.json())
  .then(data => {
    staffList = data.map(s => ({
      id: s.id,                 // keep id for updates
      name: s.fullname,
      role: s.role,
      assignments: s.assignments,
      metrics: s.metrics,
      training: s.training
    }));
    renderStaffTable();
  })
  .catch(err => console.error('Error loading staff:', err));

function renderStaffTable(filter = '') {
  staffTable.innerHTML = '';
  staffList
    .filter(s => s.name.toLowerCase().includes(filter.toLowerCase()))
    .forEach((s, index) => {
      const row = document.createElement('tr');
      row.classList.add('hover:bg-gray-50');
      row.innerHTML = `
        <td class="px-6 py-4 whitespace-nowrap">${s.name}</td>
        <td class="px-6 py-4 whitespace-nowrap"><span class="bg-cyan-100 text-cyan-800 px-2 py-1 rounded">${s.role}</span></td>
        <td class="px-6 py-4 whitespace-nowrap">${s.assignments || ''}</td>
        <td class="px-6 py-4 whitespace-nowrap">${s.metrics || ''}</td>
        <td class="px-6 py-4 whitespace-nowrap">${s.training || ''}</td>
        <td class="px-6 py-4 whitespace-nowrap flex gap-2">
          <button class="text-blue-500 hover:text-blue-700" onclick="editStaff(${index})"><i class="ri-edit-line"></i></button>
          <button class="text-red-500 hover:text-red-700" onclick="deleteStaff(${index})"><i class="ri-delete-bin-line"></i></button>
        </td>
      `;
      staffTable.appendChild(row);
    });
}

function openStaffModal(edit = false) {
  staffModal.classList.remove('hidden');
  staffModalTitle.textContent = edit ? 'Edit Staff / Volunteer' : 'Add Staff / Volunteer';
  updateRoleFieldVisibility();
}

function closeStaffModal() {
  staffModal.classList.add('hidden');
  staffForm.reset();
  editStaffIndex = null;
}

function editStaff(index) {
  editStaffIndex = index;
  const s = staffList[index];
  document.getElementById('staffName').value = s.name;
  document.getElementById('staffRole').value = s.role;
  document.getElementById('staffAssignments').value = s.assignments;
  document.getElementById('staffMetrics').value = s.metrics;
  document.getElementById('staffTraining').value = s.training;
  openStaffModal(true);
}

function deleteStaff(index) {
  if (confirm('Are you sure you want to delete this staff/volunteer?')) {
    staffList.splice(index, 1);
    renderStaffTable(staffSearch.value);
    // TODO: add API delete later if needed
  }
}

document.getElementById('addStaffBtn').addEventListener('click', () => openStaffModal());
document.getElementById('cancelStaffBtn').addEventListener('click', () => closeStaffModal());

staffForm.addEventListener('submit', e => {
  e.preventDefault();

  const name        = document.getElementById('staffName').value;
  const role        = document.getElementById('staffRole').value;
  const assignments = document.getElementById('staffAssignments').value;
  const metrics     = document.getElementById('staffMetrics').value;
  const training    = document.getElementById('staffTraining').value;

  if (!name || !role) {
    alert('Please fill in all required fields');
    return;
  }

  const formData = new FormData();
  formData.append('name', name);
  formData.append('role', role);
  formData.append('assignments', assignments);
  formData.append('metrics', metrics);
  formData.append('training', training);

  // ========= EDIT EXISTING STAFF =========
  if (editStaffIndex !== null) {
    const current = staffList[editStaffIndex];
    formData.append('action', 'update_staff');
    formData.append('id', current.id);

    fetch('/cems/api/staff.api.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.text().then(text => {
      try { return JSON.parse(text); }
      catch (e) {
        console.error('Non-JSON response from server (update):', text);
        throw new Error('Server did not return valid JSON');
      }
    }))
    .then(data => {
      if (data.status === 'success') {
        staffList[editStaffIndex] = {
          id: current.id,
          name,
          role,
          assignments,
          metrics,
          training
        };
        renderStaffTable(staffSearch.value);
        closeStaffModal();
      } else {
        console.error('Server error (update):', data);
        alert('Failed to update staff: ' + (data.message || 'Unknown error'));
      }
    })
    .catch(err => {
      console.error('Error updating staff:', err);
      alert('Error updating staff: ' + err.message);
    });

    return; // stop here for edit
  }

  // ========= ADD NEW STAFF =========
  formData.append('action', 'save_staff');

  fetch('/cems/api/staff.api.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.text().then(text => {
    try { return JSON.parse(text); }
    catch (e) {
      console.error('Non-JSON response from server (save):', text);
      throw new Error('Server did not return valid JSON');
    }
  }))
  .then(data => {
    if (data.status === 'success') {
      const newStaff = {
        id: data.id || null,
        name,
        role,
        assignments,
        metrics,
        training
      };
      staffList.push(newStaff);
      renderStaffTable(staffSearch.value);
      closeStaffModal();
    } else {
      console.error('Server error (save):', data);
      alert('Failed to save staff: ' + (data.message || 'Unknown error'));
    }
  })
  .catch(err => {
    console.error('Error saving staff:', err);
    alert('Error saving staff: ' + err.message);
  });
});

staffSearch.addEventListener('input', e => renderStaffTable(e.target.value));

renderStaffTable();
</script>
