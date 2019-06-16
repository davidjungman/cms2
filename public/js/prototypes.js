formatDateForInput = (date) => date.getFullYear()+"-"+formatMonthTwoDigits(date)+"-"+formatDayTwoDigits(date);

formatMonthTwoDigits = date => ("0" + (date.getMonth() + 1)).slice(-2);

formatDayTwoDigits = date => ("0" + date.getDate()).slice(-2);