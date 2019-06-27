using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class ZombieSpawner : MonoBehaviour {

	public GameObject zombie;

	private bool spawnOne;
	private GameObject currentZombie;
	// Use this for initialization
	void Start () {
		spawnOne = true;
		SpawnWaves();
	}

	void Update(){
		if (spawnOne == true){
			SpawnWaves();
		}
		if (currentZombie == null){
			StartCoroutine("newOne");
		}
	}

	void SpawnWaves(){
		StopCoroutine("newOne");
		currentZombie = Instantiate(zombie, transform.position, Quaternion.identity);
		spawnOne = false;
	}

	IEnumerator newOne(){
    	yield return new WaitForSeconds(5);
    	spawnOne = true;
    }

}
