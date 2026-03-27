const formatter = new Intl.NumberFormat('id-ID', {
  style: 'currency',
  currency: 'IDR',
  minimumFractionDigits: 0,
  maximumFractionDigits: 2,
})

export const currencyFormatter = (value: string | number): string => {
  if (typeof value === 'string') {
    const parsed = isNaN(Number(value)) ? 0 : Number(value)

    return formatter.format(parsed)
  }

  return formatter.format(value)
}
