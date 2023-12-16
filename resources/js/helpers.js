const getDaySuffix = (day) => {
    if (day >= 11 && day <= 13) {
        return 'th'
    }
    switch (day % 10) {
        case 1:
            return 'st'
        case 2:
            return 'nd'
        case 3:
            return 'rd'
        default:
            return 'th'
    }
}

const dayNameFromDate = (date) => {
    const dateObject = new Date(date)

    const daysOfWeek = [
        'Sunday',
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday',
    ]
    return daysOfWeek[dateObject.getDay()]
}

const getDateFromDate = (date) => {
    let newDate = new Date(date).getDate()
    return newDate + getDaySuffix(newDate)
}

export { dayNameFromDate, getDateFromDate }
