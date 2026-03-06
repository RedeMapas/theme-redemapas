import test from 'node:test'
import assert from 'node:assert/strict'
import { mapOpportunity, mapEvent } from '../assets-src/js/services/home-api.js'

test('mapOpportunity creates minimal card contract', () => {
  const mapped = mapOpportunity({ id: 7, name: 'Edital Demo', shortDescription: 'Descricao', registrationTo: '2026-04-01', singleUrl: '/oportunidade/7' })
  assert.deepEqual(mapped, { id: 7, title: 'Edital Demo', description: 'Descricao', deadline: '2026-04-01', url: '/oportunidade/7' })
})

test('mapEvent creates minimal card contract', () => {
  const mapped = mapEvent({ id: 9, name: 'Evento Demo', shortDescription: 'Descricao evento', startDate: '2026-04-10', singleUrl: '/evento/9' })
  assert.deepEqual(mapped, { id: 9, title: 'Evento Demo', description: 'Descricao evento', startsAt: '2026-04-10', url: '/evento/9' })
})
