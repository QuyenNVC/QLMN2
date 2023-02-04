export const pickerBeginDateBefore = {
    disabledDate(time) {
        return time.getTime() + 86400000 < Date.now();
    }
};
