#! /bin/bash
# based on https://github.com/GaryJones/wordpress-plugin-git-flow-svn-deploy

PLUGINSLUG='twigify'

# Set up some default values. Feel free to change these in your own script
CURRENTDIR=`pwd`
echo "CURRENT is $CURRENTDIR"
SVNPATH="/tmp/$PLUGINSLUG"
SVNURL="http://plugins.svn.wordpress.org/$PLUGINSLUG"
SVNUSER="mpvanwinkle77"
PLUGINDIR="$CURRENTDIR"
MAINFILE="$PLUGINSLUG.php"

# git config
GITPATH="$PLUGINDIR/" # this file should be in the base of your git repository

# Let's begin...
echo ".........................................."
echo 
echo "Preparing to deploy WordPress plugin"
echo 
echo ".........................................."
echo 

# Check version in readme.txt is the same as plugin file after translating both to unix line breaks to work around grep's failure to identify mac line breaks
NEWVERSION1=`grep "^Stable tag:" $GITPATH/readme.txt | awk -F' ' '{print $NF}' | tr -d '\r'`
echo "readme.txt version: $NEWVERSION1"
NEWVERSION2=`grep "Version:" $GITPATH/$MAINFILE | awk -F' ' '{print $NF}' | tr -d '\r'`
echo "$MAINFILE version: $NEWVERSION2"

if [ "$NEWVERSION1" != "$NEWVERSION2" ]; then echo "Version in readme.txt & $MAINFILE don't match. Exiting...."; exit 1; fi

echo "Versions match in readme.txt and $MAINFILE. Let's proceed..."

echo "Changing to $GITPATH"
cd $GITPATH
echo "Pushing git master to origin, with tags"
git push origin master
git push origin master --tags

echo "Creating local copy of SVN repo trunk ..."
svn checkout $SVNURL --username=$SVNUSER --password=$SVNPASS $SVNPATH  
svn update --username=$SVNUSER --password=$SVNPASS --quiet $SVNPATH/trunk --set-depth infinity

echo "Ignoring GitHub specific files"
svn propset svn:ignore "README.md
Thumbs.db
.git
.gitignore" "$SVNPATH/trunk/"

echo "Exporting the HEAD of master from git to the trunk of SVN"
git checkout-index -a -f --prefix=$SVNPATH/trunk/

# Support for the /assets folder on the .org repo.
echo "Moving assets"
# Make the directory if it doesn't already exist
mkdir -p $SVNPATH/assets/
mv $SVNPATH/trunk/assets/* $SVNPATH/assets/
svn add --force $SVNPATH/assets/
svn delete --force $SVNPATH/trunk/assets

echo "Changing directory to SVN and committing to trunk"
cd $SVNPATH/trunk/
# Delete all files that should not now be added.
svn status | grep -v "^.[ \t]*\..*" | grep "^\!" | awk '{print $2}' | xargs svn del
# Add all new files that are not set to be ignored
svn status | grep -v "^.[ \t]*\..*" | grep "^?" | awk '{print $2}' | xargs svn add
svn commit --username=$SVNUSER --password=$SVNPASS -m "Preparing for $NEWVERSION1 release"

echo "Updating WordPress plugin repo assets and committing"
cd $SVNPATH/assets/
# Delete all new files that are not set to be ignored
svn status | grep -v "^.[ \t]*\..*" | grep "^\!" | awk '{print $2}' | xargs svn del
# Add all new files that are not set to be ignored
svn status | grep -v "^.[ \t]*\..*" | grep "^?" | awk '{print $2}' | xargs svn add
svn update --accept mine-full $SVNPATH/assets/*
svn commit --username=$SVNUSER --password=$SVNPASS -m "Updating assets"

echo "Creating new SVN tag and committing it"
cd $SVNPATH
svn update --quiet $SVNPATH/tags/$NEWVERSION1
svn copy --quiet trunk/ tags/$NEWVERSION1/
# Remove assets and trunk directories from tag directory
svn delete --force --quiet $SVNPATH/tags/$NEWVERSION1/assets
svn delete --force --quiet $SVNPATH/tags/$NEWVERSION1/trunk
cd $SVNPATH/tags/$NEWVERSION1
svn commit --username=$SVNUSER --password=$SVNPASS -m "Tagging version $NEWVERSION1"

echo "Removing temporary directory $SVNPATH"
cd $SVNPATH
cd ..
rm -fr $SVNPATH/

echo "*** FIN ***"
