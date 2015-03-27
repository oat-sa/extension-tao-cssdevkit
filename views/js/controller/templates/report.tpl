<p>{{message}}</p>
<ul style="max-height: 500px; overflow: auto">
    {{#each children}}
    <li>{{this.message}}&nbsp;{{this.icon}}</li>
    {{/each}}
</ul>

