document.addEventListener('DOMContentLoaded', async () => {
 const list=document.getElementById('eventList'), search=document.getElementById('eventSearch'), category=document.getElementById('eventCategory'), sort=document.getElementById('eventSort'), status=document.getElementById('eventStatus'), error=document.getElementById('errorMessage'), info=document.getElementById('pageInfo');
 const prev=document.getElementById('prevButton'), next=document.getElementById('nextButton'); let events=[],page=0; const size=6;
 try {
  const response=await fetch('data/events.json'); if(!response.ok) throw new Error('Events could not be loaded.');
  events=await response.json();
  [...new Set(events.map(e=>e.category))].sort().forEach(c=>{const o=document.createElement('option');o.value=c;o.textContent=c;category.append(o)});
  render();
 } catch(e){status.textContent='';error.textContent=e.message+' Please try again later.'}
 function render(){
  const q=search.value.toLowerCase().trim();
  let filtered=events.filter(e=>(!q||`${e.title} ${e.location} ${e.description}`.toLowerCase().includes(q))&&(!category.value||e.category===category.value));
  filtered.sort(sort.value==='title'?(a,b)=>a.title.localeCompare(b.title):(a,b)=>a.date.localeCompare(b.date));
  const pages=Math.max(1,Math.ceil(filtered.length/size)); page=Math.min(page,pages-1); list.replaceChildren();
  filtered.slice(page*size,(page+1)*size).forEach(ev=>{
   const card=document.createElement('article');card.className='event-card';
   const date=document.createElement('span');date.className='date';date.textContent=new Date(ev.date+'T12:00:00').toLocaleDateString(undefined,{month:'short',day:'numeric',year:'numeric'});
   const title=document.createElement('h3');title.textContent=ev.title;
   const cat=document.createElement('p');cat.textContent='Category: '+ev.category;
   const loc=document.createElement('p');loc.textContent='Location: '+ev.location;
   const desc=document.createElement('p');desc.textContent=ev.description;
   card.append(date,title,cat,loc,desc);list.append(card);
  });
  status.textContent=filtered.length?`${filtered.length} event(s) found`:'No events match your search.';
  info.textContent=`Page ${page+1} of ${pages}`;prev.disabled=page===0;next.disabled=page>=pages-1;
 }
 [search,category,sort].forEach(el=>el.addEventListener('input',()=>{page=0;render()}));
 prev.addEventListener('click',()=>{if(page>0){page--;render()}});
 next.addEventListener('click',()=>{page++;render()});
});
