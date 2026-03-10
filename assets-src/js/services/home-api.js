const jsonHeaders = { Accept: 'application/json' };

async function fetchJson(url) {
  const response = await fetch(url, { headers: jsonHeaders, credentials: 'same-origin' });
  if (!response.ok) {
    throw new Error(`Request failed (${response.status}) for ${url}`);
  }
  return response.json();
}

export function mapOpportunity(item) {
  return {
    id: item.id,
    title: item.name || 'Edital sem titulo',
    description: item.shortDescription || '',
    deadline: item.registrationTo || null,
    url: item.singleUrl || `/oportunidade/${item.id}`,
  };
}

export function mapEvent(item) {
  return {
    id: item.id,
    title: item.name || 'Evento sem titulo',
    description: item.shortDescription || '',
    startsAt: item.startDate || null,
    url: item.singleUrl || `/evento/${item.id}`,
  };
}

export async function loadHomeData() {
  const [opportunitiesRes, eventsRes, statsRes] = await Promise.allSettled([
    fetchJson('/api/opportunity/find?@select=id,name,shortDescription,registrationTo,singleUrl&@limit=6&status=EQ(1)&@order=registrationTo ASC'),
    fetchJson('/api/event/find?@select=id,name,shortDescription,startDate,singleUrl&@limit=6&status=EQ(1)&@order=startDate DESC'),
    fetchJson('/api/site/stats'),
  ]);

  return {
    opportunities: opportunitiesRes.status === 'fulfilled' ? opportunitiesRes.value.map(mapOpportunity) : [],
    events: eventsRes.status === 'fulfilled' ? eventsRes.value.map(mapEvent) : [],
    stats: statsRes.status === 'fulfilled' ? statsRes.value : null,
  };
}

export async function fetchOpportunities(limit = 3) {
    const params = new URLSearchParams({
        'status': 'EQ(1)',
        '@limit': String(limit),
        '@order': 'registrationTo ASC',
        '@select': 'id,name,shortDescription,registrationFrom,registrationTo,ownerEntity',
    });
    const res = await fetch(`/api/opportunity/find?${params}`);
    if (!res.ok) return [];
    return res.json();
}
