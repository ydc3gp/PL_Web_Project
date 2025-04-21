document.addEventListener('DOMContentLoaded', function() {
	// states object mapping abbreviations to full names
	const states = {
	  "AL": "Alabama", "AK": "Alaska", "AZ": "Arizona", "AR": "Arkansas",
	  "CA": "California", "CO": "Colorado", "CT": "Connecticut", "DE": "Delaware",
	  "FL": "Florida", "GA": "Georgia", "HI": "Hawaii", "ID": "Idaho",
	  "IL": "Illinois", "IN": "Indiana", "IA": "Iowa", "KS": "Kansas",
	  "KY": "Kentucky", "LA": "Louisiana", "ME": "Maine", "MD": "Maryland",
	  "MA": "Massachusetts", "MI": "Michigan", "MN": "Minnesota", "MS": "Mississippi",
	  "MO": "Missouri", "MT": "Montana", "NE": "Nebraska", "NV": "Nevada",
	  "NH": "New Hampshire", "NJ": "New Jersey", "NM": "New Mexico", "NY": "New York",
	  "NC": "North Carolina", "ND": "North Dakota", "OH": "Ohio", "OK": "Oklahoma",
	  "OR": "Oregon", "PA": "Pennsylvania", "RI": "Rhode Island", "SC": "South Carolina",
	  "SD": "South Dakota", "TN": "Tennessee", "TX": "Texas", "UT": "Utah",
	  "VT": "Vermont", "VA": "Virginia", "WA": "Washington", "WV": "West Virginia",
	  "WI": "Wisconsin", "WY": "Wyoming", "DC": "District of Columbia"
	};
	
	// get form and state field elements
	const profileForm = document.querySelector('form');
	const stateField = document.getElementById('state_residence');
	
	// create error message element
	const errorElement = document.createElement('div');
	errorElement.id = 'state-error';
	errorElement.style.color = 'red';
	errorElement.style.display = 'none';
	errorElement.style.marginTop = '5px';
	errorElement.style.fontSize = '14px';
	
	// insert error element after state field
	stateField.parentNode.insertBefore(errorElement, stateField.nextSibling);
	
	// validate state function
	function validateState() {
	  const stateInput = stateField.value.trim().toUpperCase();
	  
	  if (stateInput === '') {
		errorElement.style.display = 'none';
		return true;
	  }
	  
	  // check if valid abbreviation -> convert to full name (jank autocomplete)
	  if (states[stateInput]) {
		stateField.value = states[stateInput];
		errorElement.style.display = 'none';
		return true;
	  }
	  
	  // check if valid full state name
	  for (const abbr in states) {
		if (states[abbr].toUpperCase() === stateInput) {
		  stateField.value = states[abbr]; // Ensure proper capitalization
		  errorElement.style.display = 'none';
		  return true;
		}
	  }
	  
	  // invalid state
	  errorElement.textContent = 'Please enter a valid US state name or abbreviation.';
	  errorElement.style.display = 'block';
	  return false;
	}
	
	// form submit event listener
	profileForm.addEventListener('submit', function(event) {
	  if (!validateState()) {
		event.preventDefault();
	  }
	});
	
	// input event listener for live validation
	stateField.addEventListener('input', function() {
	  // validate if there's enough text
	  if (this.value.length >= 2) {
		validateState();
	  } else {
		errorElement.style.display = 'none';
	  }
	});
});