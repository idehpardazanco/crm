const persianDateTimeFormatter = new Intl.DateTimeFormat(
    'fa-IR-u-ca-persian-nu-latn',
    {
        timeZone: 'Asia/Tehran',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        hour12: false,
    },
)

export function formatPersianDateTime(value: string | null | undefined): string {
    if (!value) {
        return '—'
    }

    const date = new Date(value)

    if (Number.isNaN(date.getTime())) {
        return value
    }

    return persianDateTimeFormatter.format(date)
}
