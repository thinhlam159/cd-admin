import moment from "moment";

export const convertFormData = (FormData, obj, object, nameObj) => {
    const formData = { ...obj };
    Object.keys(formData).forEach((item) => {
        if (typeof formData[item] == "object") {
            convertFormData(FormData, formData[item], true, item);
            return;
        }
        if (object) {
            FormData.append(nameObj, formData[item]);
            return;
        }
        FormData.append(item, formData[item]);
    });
};

export const convertDateByTimestamp = (timestamp) => {
    return moment.unix(timestamp).format('DD-MM-YYYY');
};
export const convertDate = (date) => {
    return moment(date).format("YYYY年M月D日 HH時mm分");
};
