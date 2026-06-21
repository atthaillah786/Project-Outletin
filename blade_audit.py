import pathlib, re
root = pathlib.Path('resources/views')
pairs = {'@if':'@endif','@foreach':'@endforeach','@forelse':'@endforelse','@for':'@endfor','@isset':'@endisset','@auth':'@endauth','@guest':'@endguest','@section':'@endsection','@push':'@endpush'}
ignore = {'@else','@elseif'}
errors = []
for path in sorted(root.rglob('*.blade.php')):
    text = path.read_text(encoding='utf-8')
    stack = []
    for num, line in enumerate(text.splitlines(), 1):
        for m in re.finditer(r'@(?:section|push|if|foreach|forelse|for|isset|empty|auth|guest|else|elseif|endif|endforeach|endforelse|endfor|endisset|endauth|endguest|endsection|endpush)\b', line):
            tok = m.group(0)
            if tok == '@section':
                if re.search(r"@section\s*\(\s*['\"]\w+['\"]\s*,", line):
                    continue
            if tok in ignore:
                continue
            if tok == '@empty':
                if stack and stack[-1][0] == '@forelse':
                    continue
                errors.append((path, num, tok, 'orphaned @empty'))
                continue
            if tok in pairs:
                stack.append((tok, num))
                continue
            if tok in pairs.values():
                if not stack or pairs[stack[-1][0]] != tok:
                    errors.append((path, num, tok, 'unexpected close '+(stack[-1][0] if stack else 'none')))
                else:
                    stack.pop()
    if stack:
        for tok, num in stack:
            errors.append((path, num, tok, 'no closing'))
if not errors:
    print('No directive stack issues found.')
else:
    for p, num, tok, msg in errors:
        print(f'{p}:{num}:{tok}:{msg}')
