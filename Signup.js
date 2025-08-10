const departmentEl = document.getElementById('department');
const semesterBlock = document.getElementById('semesterBlock');
const yearBlock = document.getElementById('yearBlock');
const subjectBlock = document.getElementById('subjectBlock');
const semesterCheckboxes = document.getElementById('semesterCheckboxes');
const yearCheckboxes = document.getElementById('yearCheckboxes');
const subjectsEl = document.getElementById('subjects');

// Subject structure
const subjectMap = {
  BCA: {
    1: ["Society & Technology", "Mathematics I", "Digital Logic", "English", "Computer Fundamentals"],
    2: ["C Programming", "Mathematics II", "Microprocessor", "Accounting", "English II"],
    3: ["Java", "Web Technology", "System Analysis and Design", "Data Structure", "Statistics"],
    4: ["Operating System", "Numerical Methods", "Software Engineering", "Scripting", "Database"],
    5: [".NET", "MIS", "Computer Networking", "Management", "Computer Graphics"],
    6: ["Mobile Programming", "Distributed System", "Applied Economics", "Advance Java", "Network programming"],
    7: ["Cyber Law and ethics", "Cloud Computing", "Elective I", "Elective II"],
    8: ["Operations Research", "Elective III", "Elective IV"]
  },
  BBM: {
    1: ["Business Mathematics I", "English I", "Microeconomics", "Sociology for Business", "Principles of Management"],
    2: ["Business Mathematics II", "English II", "Financial Accounting", "Macroeconomics", "Psychology"],
    3: ["Basic Finance", "Business Communication", "Computer Based Financial Accounting", "Business statistics", "Nepalese society and politics"],
    4: ["Accounting for decision making", "Taxation", "Financial Mgmt", "Research Methods", "Human Research Management"],
    5: ["Focus Area Course I", "Fundamentals of Marketing", "Introduction to operation management", "Legal Environment of Business", "Organizational Behavior"],
    6: ["Database Mgmt", "Focus Area II", "Business Environment in Nepal", "Introduction to International Business"],
    7: ["LBusiness Ethics", "E-commerce", "Elective Course I", "Focus Area III", "Focus Area IV"],
    8: ["Business Strategy", "Elective Areas", "Elective III", "Elective II", "Focus Area Course V", "Focus Area Courses"]
  },
  BBS: {
    1: ["Business English", "Business Statistics", "Financial Accounting", "Microeconomics for Business", "Principles of Mgmt"],
    2: ["Organizational Behavior", "Cost Accounting", "Business Communication", "Fundamentals of Financial Management", "Macro Economics"],
    3: ["Business Environment and Strategy", "Business Law", "Foundation of Financial Systems", "Marketing", "Taxation"],
    4: ["Business Research Methods", "Entrepreneurship", "Concentration Areas"]
  }
};

// Trigger pre-fill if department exists
if (typeof existingDepartment !== 'undefined' && existingDepartment) {
  departmentEl.value = existingDepartment;
  handleDepartmentChange(existingDepartment);

  // Check previously selected levels
  if (levelType === 'semester') {
    setTimeout(() => {
      Object.keys(assignments).forEach(level => {
        const checkbox = document.querySelector(`input[name="semesters[]"][value="${level}"]`);
        if (checkbox) checkbox.checked = true;
      });
      updateSubjectCheckboxes(existingDepartment, 'semester', assignments);
    }, 100); // wait for DOM to populate
  } else if (levelType === 'year') {
    setTimeout(() => {
      Object.keys(assignments).forEach(level => {
        const checkbox = document.querySelector(`input[name="years[]"][value="${level}"]`);
        if (checkbox) checkbox.checked = true;
      });
      updateSubjectCheckboxes(existingDepartment, 'year', assignments);
    }, 100);
  }
}

departmentEl.addEventListener('change', function () {
  handleDepartmentChange(this.value);
});

function handleDepartmentChange(dept) {
  semesterBlock.classList.add('hidden');
  yearBlock.classList.add('hidden');
  subjectBlock.classList.add('hidden');
  semesterCheckboxes.innerHTML = "";
  yearCheckboxes.innerHTML = "";
  subjectsEl.innerHTML = "";

  if (dept === 'BCA' || dept === 'BBM') {
    semesterBlock.classList.remove('hidden');
    generateSemesterCheckboxes(dept);
  } else if (dept === 'BBS') {
    yearBlock.classList.remove('hidden');
    generateYearCheckboxes(dept);
  }
}

function generateSemesterCheckboxes(dept) {
  for (let i = 1; i <= 8; i++) {
    const label = document.createElement('label');
    const checkbox = document.createElement('input');
    checkbox.type = 'checkbox';
    checkbox.name = 'semesters[]';
    checkbox.value = i;
    checkbox.addEventListener('change', () => updateSubjectCheckboxes(dept, 'semester'));
    label.appendChild(checkbox);
    label.append(` Semester ${i}`);
    semesterCheckboxes.appendChild(label);
  }
}

function generateYearCheckboxes(dept) {
  for (let i = 1; i <= 4; i++) {
    const label = document.createElement('label');
    const checkbox = document.createElement('input');
    checkbox.type = 'checkbox';
    checkbox.name = 'years[]';
    checkbox.value = i;
    checkbox.addEventListener('change', () => updateSubjectCheckboxes(dept, 'year'));
    label.appendChild(checkbox);
    label.append(` Year ${i}`);
    yearCheckboxes.appendChild(label);
  }
}

function updateSubjectCheckboxes(dept, mode, preselectedAssignments = null) {
  subjectBlock.classList.remove('hidden');
  subjectsEl.innerHTML = "";
  const selectedLevels = Array.from(document.querySelectorAll(`input[name="${mode === 'semester' ? 'semesters[]' : 'years[]'}"]:checked`)).map(cb => cb.value);

  selectedLevels.forEach(level => {
    const subjectList = subjectMap[dept]?.[level];
    if (subjectList) {
      const title = document.createElement('strong');
      title.textContent = `${mode === 'semester' ? 'Semester' : 'Year'} ${level}:`;
      subjectsEl.appendChild(title);

      subjectList.forEach(sub => {
        const label = document.createElement('label');
        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.name = `subjects[${level}][]`;
        checkbox.value = sub;

        // ✅ pre-select in edit mode
        if (preselectedAssignments && preselectedAssignments[level]?.includes(sub)) {
          checkbox.checked = true;
        }

        label.appendChild(checkbox);
        label.append(" " + sub);
        subjectsEl.appendChild(label);
      });
    }
  });
}
