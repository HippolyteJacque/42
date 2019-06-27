using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using UnityEngine.SceneManagement;


public class GameManager : MonoBehaviour {

	private int currentSceneIndex;
	public int enemyCount;
	private int enemiesAtStart;
	public int winOrLose = 0; // ingame is 0 / lost level is 1 / won level is 2
	public GameObject menuWin;
	public GameObject menuLose;

	public AudioClip youWin;
	public AudioClip youLose;
	public AudioClip youDied;
	public AudioClip botDied;
	private AudioSource source;

	// Use this for initialization
	void Start () {
		currentSceneIndex = SceneManager.GetActiveScene().buildIndex;
		if (currentSceneIndex > 0){
			source = GetComponent<AudioSource>();
			GameObject[] Enemies = GameObject.FindGameObjectsWithTag("enemy");
			enemyCount = Enemies.Length;
			enemiesAtStart = enemyCount;
		}
	}
	
	// Update is called once per frame
	void Update () {
		if (currentSceneIndex != 0){
			if (enemiesAtStart > enemyCount){
				source.PlayOneShot(botDied);
				enemiesAtStart--;
			}
			if (enemyCount == 0 && winOrLose < 2){
				winOrLose = 2;
			}
			if (winOrLose == 1){
				source.PlayOneShot(youLose);
				source.PlayOneShot(youDied);
				menuLose.SetActive(true);
				winOrLose = 3;
			}
			else if (winOrLose == 2){
				source.PlayOneShot(youWin);
				menuWin.SetActive(true);
				winOrLose = 3;
			}
		}
	}

	public void lauchGame(){
		SceneManager.LoadScene(1);
	}

	public void nextLevel(){
		SceneManager.LoadScene(currentSceneIndex);
	}

	public void quitGame(){
		Application.Quit();
	}

	public void Retry(){
		SceneManager.LoadScene(currentSceneIndex);
	}

	public void ReturnMenu(){
		SceneManager.LoadScene(0);
	}
}
