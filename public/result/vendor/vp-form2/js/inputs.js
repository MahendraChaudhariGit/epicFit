jQuery(document).ready(function() {
    var mInputs = [
        {
            // input type text
            type: 'text', // required
            label: '</i> Sample of <b>text input</b>?',  // required
            description: 'Description should be in here (optional).', // optional
            name: 'input-text', // required | must be unique
            placeholder: '', // optional
            value: '', // default value | optional
            icon: '', // icon or iconUrl is required
            iconUrl: 'images/user.png',  // icon (fontawesome icon class) or iconUrl is required
            isRequired: true, // Boolean | required
            rules: 'required|min:3|max:50|number' // validation rules | optional
        },
        {
            // input type email
            type: 'email', // required
            label: '</i> Sample of <b>email input</b>?',  // required
            description: 'Description should be in here (optional).', // optional
            name: 'input_email', // required | must be unique
            placeholder: '', // optional
            value: '', // default value | optional
            icon: 'fa fa-envelope-o fa-5x', // icon or iconUrl is required
            iconUrl: '',  // icon(fontawesome icon class) or iconUrl is required
            isRequired: true, // Boolean | required
            rules: 'required|min:3|max:50|number' // validation rules | optional
        },
        {
            // input type url
            type: 'url', // required
            label: '</i> Sample of <b>url input</b>?',  // required
            description: 'Description should be in here (optional).', // optional
            name: 'input-url', // required | must be unique
            placeholder: '', // optional
            value: '', // default value | optional
            icon: 'fa fa-globe fa-5x', // icon or iconUrl is required
            iconUrl: '',  // icon(fontawesome icon class) or iconUrl is required
            isRequired: true, // Boolean | required
            rules: 'required|min:3|max:50|number' // validation rules | optional
        },
        {
            // input type number
            type: 'number', // required
            label: '</i> Sample of <b>number input</b>?',  // required
            description: 'Description should be in here (optional).', // optional
            name: 'input-number', // required | must be unique
            placeholder: '', // optional
            value: '', // default value | optional
            icon: 'fa fa-phone fa-5x', // icon or iconUrl is required
            iconUrl: '',  // icon(fontawesome icon class) or iconUrl is required
            isRequired: true, // Boolean | required
            rules: 'required|min:3|max:50|number' // validation rules | optional
        },
        {
            // input type month
            type: 'month', // required
            label: '</i> Sample of <b>month input</b>?',  // required
            description: 'Description should be in here (optional).', // optional
            name: 'input-month', // required | must be unique
            placeholder: '', // optional
            value: '', // default value | optional
            icon: 'fa fa-calendar-o fa-5x', // icon or iconUrl is required
            iconUrl: '',  // icon(fontawesome icon class) or iconUrl is required
            isRequired: true, // Boolean | required
            rules: 'required|min:3|max:50|number' // validation rules | optional
        },
        {
            // input type date
            type: 'date', // required
            label: '</i> Sample of <b>date input</b>?',  // required
            description: 'Description should be in here (optional).', // optional
            name: 'input-date', // required | must be unique
            placeholder: '', // optional
            value: '', // default value | optional
            icon: 'fa fa-calendar fa-5x', // icon or iconUrl is required
            iconUrl: '',  // icon(fontawesome icon class) or iconUrl is required
            isRequired: false, // Boolean | required
            rules: 'required|min:3|max:50|number' // validation rules | optional
        },
        {
            // input type rating
            type: 'rating', // required
            label: '</i> Sample of <b>rating input</b>?',  // required
            description: 'Description should be in here (optional).', // optional
            name: 'input-rating', // required | must be unique
            value: '', // default value | optional
            icon: 'fa fa-star-o fa-5x', // required
            iconFill: 'fa fa-star fa-5x', // required
            itemCount: 5, // required | as much star you want
            isRequired: true, // Boolean | required
            rules: 'required|min:3|max:50|number' // validation rules | optional
        },
        {
            // input type rating
            type: 'rating', // required
            label: '</i> Sample of <b>rating input heart</b>?',  // required
            description: 'Description should be in here (optional).', // optional
            name: 'input-rating-heart', // required | must be unique
            value: '', // default value | optional
            icon: 'fa fa-heart-o fa-5x', // required
            iconFill: 'fa fa-heart fa-5x', // required
            itemCount: 6, // required | as much star you want
            isRequired: true, // Boolean | required
            rules: 'required|min:3|max:50|number' // validation rules | optional
        },
        {
            // input type rating number
            type: 'rating-number', // required
            label: '</i> Sample of <b>rating number input</b>?',  // required
            description: 'Description should be in here (optional).', // optional
            name: 'input-rating-number', // required | must be unique
            value: '', // default value | optional
            itemCount: 6, // required | as much star you want
            isRequired: true, // Boolean | required
            rules: 'required|min:3|max:50|number' // validation rules | optional
        },
        {
            // input type radio
            type: 'radio', // required
            label: '</i> Sample of <b>radio input</b>?',  // required
            description: 'Description should be in here (optional).', // optional
            name: 'input-radio', // required | must be unique
            value: '', // default value | optional
            icon: 'fa fa-venus-mars fa-5x', // icon or iconUrl is required
            iconUrl: '',  // icon(fontawesome icon class) or iconUrl is required
            isRequired: true, // Boolean | required
            rules: 'required|min:3|max:50|number', // validation rules | optional
            activeOption: -1, // index of option, if want to keep option unselected set the value to -1
            keySelectionEnabled: true, // Boolean | required to select option by key
            options: [
                {
                    icon: 'fa fa-male', // required
                    key: 'A', // optional
                    label: 'Male', // required
                    value: 'Male', // required
                },
                {
                    icon: 'fa fa-female',  // required
                    key: 'B',  // optional
                    label: 'Female',  // required
                    value: 'Female',  // required
                },
                {
                    icon: 'fa fa-comments',  // required
                    key: 'C',  // optional
                    label: 'Other',  // required
                    value: 'Other',  // required
                    customValueEnabled: true, // boolean | optional
                }
            ]
        },
        {
            // input type radio
            type: 'radio-vertical', // required
            label: '</i> Sample of <b>radio vertical input</b>?',  // required
            description: 'Description should be in here (optional).', // optional
            name: 'input-radio-vertical', // required | must be unique
            value: '', // default value | optional
            icon: 'fa fa-optin-monster fa-5x', // icon or iconUrl is required
            iconUrl: '',  // icon(fontawesome icon class) or iconUrl is required
            isRequired: true, // Boolean | required
            rules: 'required|min:3|max:50|number', // validation rules | optional
            activeOption: -1, // index of option, if want to keep option unselected set the value to -1
            keySelectionEnabled: true, // Boolean | required to select option by key
            options: [
                {
                    icon: 'fa fa-check', // required
                    key: 'A', // optional
                    label: 'Male', // required
                    value: 'Male', // required
                },
                {
                    icon: 'fa fa-check',  // required
                    key: 'B',  // optional
                    label: 'Female',  // required
                    value: 'Female',  // required
                }
            ]
        },
        {
            // input type checkbox
            type: 'checkbox', // required
            label: '</i> Sample of <b>checkbox input</b>?',  // required
            description: 'Description should be in here (optional).', // optional
            name: 'input-checkbox[]', // required | must be unique
            value: [], // default value | optional
            icon: 'fa fa-list-alt fa-5x', // icon or iconUrl is required
            iconUrl: '',  // icon(fontawesome icon class) or iconUrl is required
            isRequired: true, // Boolean | required
            rules: 'required|min:3|max:50|number', // validation rules | optional
            activeOptions: [], // index of option, if want to keep option unselected set the value to -1
            keySelectionEnabled: true, // Boolean | required to select option by key
            options: [
                {
                    icon: 'fa fa-male', // required
                    key: 'A', // optional
                    label: 'Red', // required
                    value: 'Red', // required
                },
                {
                    icon: 'fa fa-female',  // required
                    key: 'B',  // optional
                    label: 'Green',  // required
                    value: 'Green',  // required
                },
                {
                    icon: 'fa fa-female',  // required
                    key: 'C',  // optional
                    label: 'Blue',  // required
                    value: 'Blue',  // required
                },
                {
                    icon: 'fa fa-female',  // required
                    key: 'D',  // optional
                    label: 'Yellow',  // required
                    value: 'Yellow',  // required
                },
                {
                    icon: 'fa fa-female',  // required
                    key: 'E',  // optional
                    label: 'Gray',  // required
                    value: 'Gray',  // required
                },
                {
                    icon: 'fa fa-comments',  // required
                    key: 'F',  // optional
                    label: 'Other',  // required
                    value: 'Other',  // required
                    customValueEnabled: true, // boolean | optional
                }
            ]
        },
        {
            // input type checkbox
            type: 'checkbox-vertical', // required
            label: '</i> Sample of <b>checkbox vertical input</b>?',  // required
            description: 'Description should be in here (optional).', // optional
            name: 'input-checkbox-vertical[]', // required | must be unique
            value: [], // default value | optional
            icon: 'fa fa-list-alt fa-5x', // icon or iconUrl is required
            iconUrl: '',  // icon(fontawesome icon class) or iconUrl is required
            isRequired: true, // Boolean | required
            rules: 'required|min:3|max:50|number', // validation rules | optional
            activeOptions: [], // index of option, if want to keep option unselected set the value to -1
            keySelectionEnabled: true, // Boolean | required to select option by key
            options: [
                {
                    icon: 'fa fa-check', // required
                    key: 'A', // optional
                    label: 'Red', // required
                    value: 'Red', // required
                },
                {
                    icon: 'fa fa-check',  // required
                    key: 'B',  // optional
                    label: 'Green',  // required
                    value: 'Green',  // required
                },
                {
                    icon: 'fa fa-check',  // required
                    key: 'C',  // optional
                    label: 'Blue',  // required
                    value: 'Blue',  // required
                }
            ]
        },
        {
            // input type file
            type: 'file', // required
            label: '</i> Sample of <b>file (image) input</b>?',  // required
            description: 'Description should be in here (optional).', // optional
            name: 'input-file', // required | must be unique
            value: '', // default value | optional
            icon: 'fa fa-picture-o fa-5x', // icon or iconUrl is required
            iconUrl: '',  // icon(fontawesome icon class) or iconUrl is required
            isRequired: false, // Boolean | required
            rules: 'required|min:3|max:50|number' // validation rules | optional
        },
        {
            // input type text
            type: 'textarea', // required
            label: '</i> Sample of <b>textarea input</b>?',  // required
            description: 'Description should be in here (optional).', // optional
            name: 'input-text-area', // required | must be unique
            placeholder: '', // optional
            value: '', // default value | optional
            icon: 'fa fa-file-text-o fa-5x', // icon or iconUrl is required
            iconUrl: '',  // icon (fontawesome icon class) or iconUrl is required
            isRequired: true, // Boolean | required
            rules: 'required|min:3|max:50|number' // validation rules | optional
        },
    ];

    window.initVpForm(mInputs);
});
