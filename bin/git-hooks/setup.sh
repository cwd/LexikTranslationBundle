#!/bin/bash

cp bin/git-hooks/pre-commit .git/hooks/pre-commit
chmod +x .git/hooks/pre-commit

cp bin/git-hooks/post-merge .git/hooks/post-merge
chmod +x .git/hooks/post-merge

cp bin/git-hooks/post-checkout .git/hooks/post-checkout
chmod +x .git/hooks/post-checkout
