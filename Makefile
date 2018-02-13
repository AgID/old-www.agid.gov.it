deploy-prod:
	ansible-playbook -i deploy/prod/hosts --ssh-common-args='-o StrictHostKeyChecking="no"' deploy/prod/deploy.yml
