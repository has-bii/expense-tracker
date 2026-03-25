import type { LocationQueryValue } from 'vue-router'

export const parseQuery = (
  query: LocationQueryValue | LocationQueryValue[] | undefined,
  defaultValue: string,
): string => {
  if (typeof query === 'undefined') return defaultValue

  if (Array.isArray(query)) {
    if (query.length > 0) return query[0]?.toString() ?? defaultValue
    return defaultValue
  }

  return query?.toString() ?? defaultValue
}
