// Carga y gestión de posts
document.addEventListener('DOMContentLoaded', ()=>{
  const postsContainer = document.getElementById('posts');
  const form = document.getElementById('postForm');
  if(postsContainer) loadPosts();
  if(form) form.addEventListener('submit', e=>{
    e.preventDefault();
    alert('Al guardar, actualiza manualmente data/posts.txt con el siguiente bloque: ' +
      `\n---\n${form.title.value}\n${form.content.value}\n---`);
    form.reset(); loadPosts();
  });
});

function loadPosts(){
  fetch('data/posts.txt')
    .then(r=>r.text())
    .then(text=>{
      const blocks = text.split('---').filter(s=>s.trim());
      const html = blocks.map((b,i)=>{
        const [title, ...rest] = b.trim().split('\n');
        return `<article><h4><a href="view.html?id=${i}">${title}</a></h4></article>`;
      }).join('');
      document.getElementById('posts').innerHTML = html;
    });
}

function loadSinglePost(){
  const params = new URLSearchParams(location.search);
  const id = params.get('id');
  fetch('data/posts.txt')
    .then(r=>r.text())
    .then(text=>{
      const blocks = text.split('---').filter(s=>s.trim());
      if(blocks[id]){
        const [title, ...rest] = blocks[id].trim().split('\n');
        document.getElementById('postView').innerHTML = `<h2>${title}</h2><p>${rest.join('\n')}</p>`;
      }
    });
}
if(document.getElementById('postView')) loadSinglePost();