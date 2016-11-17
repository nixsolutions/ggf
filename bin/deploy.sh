#!/bin/bash
# -w - workspace directory on build server
# -u - deploy user on remote server
# -s - server host to deploy to
# -e - app environment
# -c - current commit

while getopts w:u:s:e:c:r: flag; do
    case $flag in
        w)
            workspace=$OPTARG;
            ;;
        u)
            user=$OPTARG;
            ;;
        s)
            host="$OPTARG";
            ;;
        e)
            APP_ENV="$OPTARG";
            ;;
        c)
            currentCommit=$OPTARG;
            ;;
        r)
            server="$OPTARG";
            ;;
        ?)
            exit;
            ;;
    esac
done

if [ $APP_ENV == "demo" ]; then
    deployDir="/home/dev/www_files/ggf_${APP_ENV}"
    exec_string="$user@$server:$deployDir"
else
    deployDir="/home/dev/src/ggf_${APP_ENV}"
    exec_string=$deployDir
fi

echo "DEPLOY DIR ======>  $deployDir   "
echo "HOST       ======>  $host       "

echo -e "\tSyncing data in $deployDir with git tag $currentCommit"

echo  " DEFAULT DEPLOY "

rsync -aP --no-o --no-g --delete --progress $workspace/ $exec_string \
--exclude /.buildpacks \
--exclude /.gitattributes \
--exclude /.gitignore \
--exclude /.travis.yml \
--exclude /after.sh \
--exclude /Procfile \
--exclude /readme.md \
--exclude /Homestead.yaml \
--exclude /server.php \
--exclude /Vagrantfile \
--exclude /vendor/ \
--exclude /.vagrant/ \
--exclude /node_modules/ \
--exclude /resources/frontend/node_modules/ \
--exclude /resources/frontend/bower-components \
--exclude /resources/frontend/tmp \
--exclude /.env \
--exclude /.env.testing \
--exclude /build/ \
--exclude /public/assets \
--exclude /public/font \
--exclude /public/leagues-logo \
--exclude /public/teams-logo \
--exclude /public/vendor \
--exclude /storage/app \
--exclude /storage/api-docs \
--exclude /storage/debugbar \
--exclude /storage/logs \
--exclude /resources/views/app.blade.php \

echo -e "\tRunning install script/smoke tests"\
cd ${deployDir} ; \
chmod -v +x $deployDir/bin/post-install.sh ${host} ; \
$deployDir/bin/post-install.sh -e ${APP_ENV} ;
