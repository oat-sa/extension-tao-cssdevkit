<p>{{message}}</p>
<ul style="max-height: 500px; overflow: auto">
    {{#each children}}
    <li>{{this.message}}</li>
    {{/each}}
</ul>
