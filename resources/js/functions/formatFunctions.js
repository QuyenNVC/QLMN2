export const formatString = str => {
    str = removeAscent(str).trim();
    return str;
};

function removeAscent(str) {
    if (str === null || str === undefined) return str;
    str = str.toLowerCase();
    str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
    str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
    str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
    str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
    str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
    str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
    str = str.replace(/đ/g, "d");
    return str;
}

export const formatMoney = num => {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
};

export const formatDatedmY = date => {
    return (
        new Date(date).getDate().toLocaleString("en-US", {
            minimumIntegerDigits: 2,
            useGrouping: false
        }) +
        "/" +
        (new Date(date).getMonth() + 1).toLocaleString("en-US", {
            minimumIntegerDigits: 2,
            useGrouping: false
        }) +
        "/" +
        new Date(date).getFullYear()
    );
};

export const convertDateToMonthAndYear = date => {
    return {
        month: date.getMonth() + 1,
        year: date.getFullYear()
    };
};
