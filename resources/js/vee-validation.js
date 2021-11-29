import { extend, configure } from "vee-validate";
import { required, email, min, confirmed, alpha_dash } from "vee-validate/dist/rules";

configure({
    classes: {
        invalid: "form-error"
    }
});

extend("email", email);

extend("required", {
    ...required,
    message: "{_field_} is required"
});

extend("min", min);

extend("confirmed", {
    ...confirmed,
    message: "{_field_} does not match"
});

extend("alpha_dash", alpha_dash);