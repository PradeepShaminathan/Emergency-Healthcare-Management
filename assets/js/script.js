let userDetails;
let allocatedBeds = 0;
let patientsDataTable;
let patientsBedDataTable;

// Function to clear all input fields
const clearInputs = () => {
    const input = document.querySelectorAll("input");
    Object.keys(input).map(ele => {
        input[ele].value = "";
        if (input[ele].checked) {
            input[ele].checked = false;
        }
    });
    const selected = document.querySelectorAll("select");
    Object.keys(selected).map(ele => selected[ele].selectedIndex = 0);
    const textArea = document.querySelectorAll("textarea");
    Object.keys(textArea).map(ele => textArea[ele].value = '');

    // defaults
    document.querySelector('#Critical').checked = true;
    document.querySelector("#date").value = new Date().toISOString().slice(0, -14);
}

// Function to switch authentication forms
const toggleForm = (signInForm) => {
    clearInputs();
    const signin = document.querySelectorAll(".signin");
    const signup = document.querySelectorAll(".signup");
    if (signInForm) {
        Object.keys(signin).map(ele => signin[ele].hidden = signInForm);
        Object.keys(signup).map(ele => signup[ele].hidden = !signInForm);
    } else {
        Object.keys(signup).map(ele => signup[ele].hidden = !signInForm);
        Object.keys(signin).map(ele => signin[ele].hidden = signInForm);
    }
}

// Function to fetch values of all input fields
const fetchInputs = (type) => {
    let inputFields = {}
    switch (type) {
        case "login":
            {
                inputFields.email = document.querySelector("#email").value;
                inputFields.password = document.querySelector("#password").value;
                inputFields.userId = document.querySelector("#idNumber").value;
                break;
            }
        case "signup":
            {
                inputFields.email = document.querySelector("#email").value;
                inputFields.password = document.querySelector("#password").value;
                inputFields.role = document.querySelector('input[name="role"]:checked').id;
                inputFields.userId = document.querySelector("#idNumber").value;
                inputFields.firstName = document.querySelector("#firstName").value;
                inputFields.lastName = document.querySelector("#lastName").value;
                inputFields.roleConfig = inputFields.role === "Hospital" ? JSON.stringify({
                    role: "Hospital",
                    roleId: "H-00" + inputFields.userId,
                    name: document.querySelector("#hospitalName").value,
                    bedCount: document.querySelector("#bedCount").value,
                }) : JSON.stringify({
                    role: "Clinic",
                    rolelId: "C-00" + inputFields.userId,
                    name: document.querySelector("#hospitalName").value
                });
                break;
            }
        case "patient":
            {
                inputFields.date = document.querySelector("#date").value;
                inputFields.place = document.querySelector("#place").value;
                inputFields.name = document.querySelector("#name").value;
                inputFields.address = document.querySelector("#address").value;
                inputFields.phoneNumber = document.querySelector("#phoneNumber").value;
                inputFields.dob = document.querySelector("#dob").value;
                inputFields.age = document.querySelector("#age").value;
                inputFields.severity = document.querySelector('input[name="patientContion"]:checked').id;
                inputFields.gender = document.querySelector("#gender").value;
                inputFields.bloodGroup = document.querySelector("#bloodGroup").value;
                inputFields.symptoms = document.querySelector("#symptoms").value;
                inputFields.assignedHospital = document.querySelector("#selectHospital").value;
                // image, document
                break;
            }
        default:
            break;
    }
    return inputFields;
}

// Function to authenticate user
const authenticateUser = async() => {
    const params = fetchInputs("login");
    const response = await fetch("./server/validateUser.php", {
        method: 'POST',
        body: JSON.stringify(params),
        headers: new Headers({
            'Content-Type': 'application/json; charset=UTF-8'
        })
    });
    const jsonRes = await response.json();
    if (jsonRes.success && jsonRes.isValidUser) {
        userDetails = jsonRes.users[0];
        userDetails.fullName = userDetails.firstName + " " + userDetails.lastName
        document.querySelector("#userName").innerText = userDetails.fullName;
        document.querySelector("#login").style.display = 'none';
        document.querySelector("#navbar").style.display = 'block';
        document.querySelector("#patient").style.display = 'block';
        if (userDetails.role === "Hospital") {
            JSON.parse(userDetails.roleConfig)
            userDetails.roleConfig = JSON.parse(userDetails.roleConfig);
            document.querySelector("#patientBedLink").hidden = false;
            document.querySelector("#hospitalNameDisplay").innerText = userDetails.roleConfig.name;
            document.querySelector("#hospitalIdDisplay").innerText = userDetails.roleConfig.roleId;
            document.querySelector("#totalBedsDisplay").innerText = userDetails.roleConfig.bedCount;
        }
        clearInputs();
    } else {
        alert(jsonRes.message);
    }
}

// Function to register user
const registerUser = async() => {
    const params = fetchInputs("signup");
    const response = await fetch("./server/createUser.php", {
        method: 'POST',
        body: JSON.stringify(params),
        headers: new Headers({
            'Content-Type': 'application/json; charset=UTF-8'
        })
    });
    const jsonRes = await response.json();
    if (jsonRes.success) {
        toggleForm(false);
        clearInputs();
        alert("Registration Success!");
    } else {
        alert(jsonRes.message);
    }
}

// Function to logout
const logout = () => {
    document.querySelector("#login").style.display = 'block';
    document.querySelector("#navbar").style.display = 'none';
    document.querySelector("#patient").style.display = 'none';
    clearInputs();
    window.location.reload();
}

// Function to register patient
const addPatientDetails = async() => {
    const params = fetchInputs("patient");
    params.creatorEmail = userDetails.email + '÷' + userDetails.roleConfig.name;
    params.creatorName = userDetails.fullName;
    const response = await fetch("./server/createPatient.php", {
        method: 'POST',
        body: JSON.stringify(params),
        headers: new Headers({
            'Content-Type': 'application/json; charset=UTF-8'
        })
    });
    const jsonRes = await response.json();
    if (jsonRes.success) {
        toggleForm(false);
        clearInputs();
        alert("Patient Registered!");
    } else {
        alert(jsonRes.message);
    }
}

// Function to register patient
const fetchPatients = async(allPatients) => {
    const response = await fetch("./server/fetchPatients.php", {
        method: 'POST',
        body: JSON.stringify({ "creatorEmail": allPatients ? "" : userDetails.email, "assignedHospitalId": userDetails.roleConfig.roleId }),
        headers: new Headers({
            'Content-Type': 'application/json; charset=UTF-8'
        })
    });
    const jsonRes = await response.json();
    if (jsonRes.success) {
        loadPatients(jsonRes.patients, allPatients);
        clearInputs();
    } else {
        alert(jsonRes.message);
    }
}

// Function to register patient
const fetchHospitals = async() => {
    const response = await fetch("./server/fetchHospitals.php", {
        method: 'POST',
        headers: new Headers({
            'Content-Type': 'application/json; charset=UTF-8'
        })
    });
    const jsonRes = await response.json();
    if (jsonRes.success) {
        loadHospitals(jsonRes.hospitals);
    } else {
        alert(jsonRes.message);
    }
}

// Function to load hospitals
const loadHospitals = (hospitals) => {
    let optionHTML = `<option selected>Select Hospital</option>`;
    hospitals.map(hospital => {
        let hospitalDetails = JSON.parse(hospital.roleConfig);
        optionHTML += `<option value="${hospitalDetails.roleId}">${hospitalDetails.roleId} - ${hospitalDetails.name}</option>`;
    })
    document.querySelector("#selectHospital").innerHTML = optionHTML;
}

// Function to switch between main forms
const togglePage = (listPage) => {
    const input = document.querySelectorAll("input");
    Object.keys(input).map(ele => input[ele].value = "");
    switch (listPage) {
        case 3:
            {
                fetchPatients(true);
                document.querySelector("#patientsEntry").hidden = true;
                document.querySelector("#patientsList").hidden = true;
                document.querySelector("#patientBedAllocation").hidden = false;
                document.querySelector("#patientListLink").classList.remove("active");
                document.querySelector("#patientEntryLink").classList.remove("active");
                document.querySelector("#patientBedLink").classList.add("active");
                break;
            }
        case 2:
            {
                fetchPatients(false);
                document.querySelector("#patientsEntry").hidden = true;
                document.querySelector("#patientsList").hidden = false;
                document.querySelector("#patientBedAllocation").hidden = true;
                document.querySelector("#patientListLink").classList.add("active");
                document.querySelector("#patientEntryLink").classList.remove("active");
                document.querySelector("#patientBedLink").classList.remove("active");
                break;
            }
        default:
            {
                fetchHospitals();
                document.querySelector("#patientsEntry").hidden = false;
                document.querySelector("#patientsList").hidden = true;
                document.querySelector("#patientBedAllocation").hidden = true;
                document.querySelector("#patientListLink").classList.remove("active");
                document.querySelector("#patientEntryLink").classList.add("active");
                document.querySelector("#patientBedLink").classList.remove("active");
                // set maximum and values to date field
                document.querySelector("#date").max = new Date().toISOString().slice(0, -14);
                document.querySelector("#date").value = new Date().toISOString().slice(0, -14);
                // setting maximum to date of birth
                document.querySelector("#dob").max = new Date().toISOString().slice(0, -14);
            }
    }
}

// UI actions for navbar
document.querySelectorAll('.dropdown-toggle').forEach(item => {
    item.addEventListener('click', event => {
        if (event.target.classList.contains('dropdown-toggle')) {
            event.target.classList.toggle('toggle-change');
        } else if (event.target.parentElement.classList.contains('dropdown-toggle')) {
            event.target.parentElement.classList.toggle('toggle-change');
        }
    })
});

// Function to load patients data
const loadPatients = async(patients, hospital) => {
    let tbodyHTML = ``;
    allocatedBeds = userDetails.roleConfig.bedCount;
    await patients.map((patient, i) => {
        allocatedBeds = patient.allocatedHospitalId === userDetails.roleConfig.roleId ? allocatedBeds - 1 : allocatedBeds;
        let creatorName = patient.creatorEmail.split('÷');
        tbodyHTML += `<tr>
                        <td>${i + 1}</td>
                        <td>${patient.fullName}</td>
                        <td>${patient.recordId}</td>
                        ${ hospital ? `<td>${creatorName[1] ? creatorName[1] : creatorName[0]}</td>` : ``}
                        <td>${patient.symptoms}</td>
                        <td>${patient.date}</td>
                        <td>${patient.severity}</td>
                        <td>${patient.allocatedHospitalId ? patient.allocatedHospitalId : "N/A"}</td>
                        <td id="act${patient.recordId}">${patient.allocatedHospitalId ? 'Allocated' : (hospital ? '<button class="btn btn-sm btn-primary" onclick="allocateHospital(' + patient.recordId + ')">Allocate</button>' : 'Waiting')}</td>
                    </tr>`
    });
    document.querySelector("#allocatedBedsDisplay").innerHTML = allocatedBeds;
    document.querySelector("#availableBedsDisplay").innerHTML = userDetails.roleConfig.bedCount - allocatedBeds;
    const table = hospital ? "#patientsBedTable" : "#patientsTable";
    $(table).DataTable().destroy();
    document.querySelector(table + "Body").innerHTML = tbodyHTML;
    patientsBedDataTable = $(table).DataTable();
}

// Function to allocate Hospital
const allocateHospital = async(recordId) => {
    const response = await fetch("./server/allocateHospital.php", {
        method: 'POST',
        body: JSON.stringify({ "allocatedHospitalId": userDetails.roleConfig.roleId, "recordId": recordId }),
        headers: new Headers({
            'Content-Type': 'application/json; charset=UTF-8'
        })
    });
    const jsonRes = await response.json();
    if (jsonRes.success) {
        document.querySelector("#act" + recordId).innerText = "Allocated";
        allocatedBeds -= 1;
        document.querySelector("#allocatedBedsDisplay").innerHTML = allocatedBeds;
        document.querySelector("#availableBedsDisplay").innerHTML = userDetails.roleConfig.bedCount - allocatedBeds;
        fetchPatients(true);
        clearInputs();
    } else {
        alert(jsonRes.message);
    }
}

// Function to load profile image
const loadProfileImage = (event) => {
    document.querySelector("#output").src = URL.createObjectURL(event.target.files[0]);
    document.querySelector("#output").style.visibility = "visible";
};

// Toggle display number of fields
const toggleInput = (hospital) => {
    document.querySelector("#bedCount").disabled = !hospital;
}

// Function to show data tables
$('#patientListLink').click(() => $('#patientsTable').DataTable());
$('#patientBedLink').click(() => $('#patientsBedTable').DataTable());
fetchHospitals();
