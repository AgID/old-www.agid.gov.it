deploy-prod:
	ansible-playbook -i deploy/prod/hosts --ssh-common-args='-o StrictHostKeyChecking="no"' deploy/prod/deploy.yml

deploy-stage:
	ansible-playbook -i deploy/stage/hosts --ssh-common-args='-o StrictHostKeyChecking="no"' deploy/stage/deploy.yml
